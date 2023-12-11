<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "customer".
 *
 * @property int $customer_id
 * @property int $restaurant_uuid
 * @property string $customer_name
 * @property string $country_code
 * @property string $customer_phone_number
 * @property string|null $customer_email
 * @property string $customer_created_at
 * @property string $customer_updated_at
 * @property string $civil_id
 * @property string $section
 * @property string $class
 *
 * @property Order[] $orders
 * @property CustomerVoucher[] $customerVouchers
 * @property Restaurant $restaurant
 */
class Customer extends \yii\db\ActiveRecord implements IdentityInterface {

    const EMAIL_VERIFIED = 1;
    const EMAIL_NOT_VERIFIED = 0;

    const SCENARIO_CHANGE_PASSWORD = 'change-password';
    const SCENARIO_CREATE_NEW_AGENT = 'create';
    const SCENARIO_UPDATE_EMAIL = 'update-email';
    const SCENARIO_VERIFY_EMAIL = 'verify-email';
    const SCENARIO_DELETE = 'delete';

    /**
     * Field for temporary password. If set, it will overwrite the old password on save
     * @var string
     */
    public $tempPassword;
    //for report
    public $totalSpent;
    public $totalOrder;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['customer_name', 'customer_phone_number'], 'required'],// 'customer_phone_number','country_code'
            //'customer_email', 
            [['restaurant_uuid'], 'string', 'max' => 60],

            //[['customer_phone_number'], 'unique', 'comboNotUnique' => Yii::t('app', 'Phone no. already exist.'),
            //    'targetAttribute' => ['restaurant_uuid']],//, 'customer_phone_number' => 'country_code',

            //[['customer_email'], 'unique', 'comboNotUnique' => Yii::t('app', 'Phone no. already exist.'),
            //    'targetAttribute' => ['restaurant_uuid']],

            [['customer_email'], 'validateEmail'],
            [['customer_phone_number'], 'validatePhone'],

            [['ip_address'], 'string', 'max' => 45],

            //[['customer_phone_number'], 'unique'],
            //[['customer_email'], 'unique'],

            [['customer_email'], 'email'],
            [['country_code'], 'integer'],
            [['customer_phone_number'], 'string', 'min' => 5, 'max' => 20],
            [['customer_created_at','customer_updated_at'], 'safe'],
            [['customer_name', 'customer_email'], 'string', 'max' => 255],
            [['civil_id', 'section','class'], 'string', 'max' => 255], //Temp fields
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validatePhone($attribute, $params, $validator)
    {
        $query = self::find()
            ->andWhere([
                'restaurant_uuid' => $this->restaurant_uuid,
                'customer_phone_number' => $this->customer_phone_number,
                'country_code' => $this->country_code,
                'deleted' => 0
            ]);

        if($this->customer_id) {
            $query->andWhere(['!=', 'customer_id', $this->customer_id]);
        }

        if ($query->exists()) {
            $this->addError($attribute, 'Phone number has already been taken.');
            return false;
        }

        return true;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @return bool
     */
    public function validateEmail($attribute, $params, $validator)
    {
        $query = self::find()
            ->andWhere([
                'restaurant_uuid' => $this->restaurant_uuid,
                'customer_email' => $this->customer_email,
                'deleted' => 0
            ]);

       if($this->customer_id) {
           $query->andWhere(['!=', 'customer_id', $this->customer_id]);
       }

        if ($query->exists()) {
            $this->addError($attribute, 'Email has already been taken.');
            return false;
        }

        return true;
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios ();

        $scenarios['updateLanguagePref'] = ['customer_language_pref', 'ip_address'];

        $scenarios['update-email'] = ['customer_email', 'customer_new_email', 'ip_address'];

        $scenarios['verify-email'] = ['customer_email', 'customer_new_email', 'customer_email_verification', 'customer_auth_key', 'ip_address'];

        $scenarios['SCENARIO_DELETE'] = ['deleted', 'ip_address'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'customer_created_at',
                'updatedAtAttribute' => 'customer_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'customer_id' => Yii::t('app','Customer ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant UUID'),
            'customer_name' => Yii::t('app','Customer Name'),
            'customer_phone_number' => Yii::t('app','Phone Number'),
            'country_code' => Yii::t('app','Country Code'),
            'customer_email' => Yii::t('app','Customer Email'),
            'customer_created_at' => Yii::t('app','Customer Created At'),
            'customer_updated_at' => Yii::t('app','Customer Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        // Get initial IP address of requester
        $ip = Yii::$app->request->getRemoteIP();

        // Check if request is forwarded via load balancer or cloudfront on behalf of user
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'];

            // as "X-Forwarded-For" is usually a list of IP addresses that have routed
            $IParray = array_values(array_filter(explode(',', $forwardedFor)));

            // Get the first ip from forwarded array to get original requester
            $ip = $IParray[0];
        }

        $this->ip_address = $ip;

        return true;
    }

    public static function getTotalCustomersByWeek()
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer`',
        ]);
        
        $customer_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $customer_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function($db) {

            return Customer::find()
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere(new Expression("DATE(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->groupBy(new Expression('DAYNAME(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date ('w', strtotime ($result['customer_created_at']))] = array(
                'day' => date ('D', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function($db) {

            return Customer::find()
                ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public static function getTotalCustomersByMonth()
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer`',
        ]);
        
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')).'-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $customer_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function($db) {

            return Customer::find()
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->groupBy(new Expression('DAY(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date ('j', strtotime ($result['customer_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function($db) {

            return Customer::find()
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public static function getTotalCustomersByMonths($months)
    {
        $cacheDuration = 60 * 60 * 24 * 365;// 365 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `customer`',
        ]);
        
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $customer_data[$month] = array(
                'month'   => date('F', strtotime('-'.($months - $i).' month')),
                'total' => 0
            );
        }

        $rows = Customer::getDb()->cache(function($db) use($months) {

            return Customer::find()
                ->select(new Expression('customer_created_at, COUNT(*) as total'))
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL '.$months.' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->groupBy(new Expression('MONTH(customer_created_at)'))
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        foreach ($rows as $result) {
            $customer_data[date ('m', strtotime ($result['customer_created_at']))] = array(
                'month' => Yii::t('app', date ('F', strtotime ($result['customer_created_at']))),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::getDb()->cache(function($db) use($months) {

            return Customer::find()
                ->andWhere('`customer_created_at` >= (NOW() - INTERVAL '.$months.' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
                ->count();

        }, $cacheDuration, $cacheDependency);

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public static function getTotalCustomersByInterval($interval) {
        switch ($interval) {
            case "last-month":
                return self::getTotalCustomersByMonth();
            case "week":
                return self::getTotalCustomersByWeek();
            default:
                return self::getTotalCustomersByMonths(str_replace(["last-", "-months"], ["", ""], $interval));
        }
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @param $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerAddresses($modelClass = "\common\models\CustomerAddress") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
            ->activeOrders($this->restaurant_uuid);
    }

    /**
     * @param string $modelClass
     * @return mixed
     */
    public function getTotalSpent($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
            ->activeOrders($this->restaurant_uuid)
            ->sum('total_price');
    }

    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers($modelClass = "\common\models\CustomerVoucher")
    {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id'])
            ->via('restaurant');
    }


    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        $filter = ['customer_email' => $email, 'deleted' => 0];

        if($store_id)
            $filter['restaurant_uuid'] = $store_id;

        return static::findOne ($filter);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid ($token)) {
            return null;
        }

        return static::findOne ([
            'customer_password_reset_token' => $token,
            'deleted' => 0,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr ($token, strrpos ($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time ();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey ();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->customer_auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey () === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword ($password, $this->customer_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->customer_password_hash = Yii::$app->security->generatePasswordHash ($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->customer_auth_key = Yii::$app->security->generateRandomString ();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->customer_password_reset_token = Yii::$app->security->generateRandomString () . '_' . time ();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->customer_password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne ([
            'customer_id' => $id,
            'customer_email_verification' => true,
            'deleted' => 0
        ]);
    }

    /**
     * Create an Access Token Record for this customer
     * if the customer already has one, it will return it instead
     * @return \common\models\CustomerToken
     */
    public function getAccessToken()
    {
        // Return existing inactive token if found
        $token = \api\models\CustomerToken::findOne ([
            'customer_id' => $this->customer_id,
            'token_status' => CustomerToken::STATUS_ACTIVE
        ]);

        if ($token) {
            return $token;
        }

        // Create new inactive token

        $token = new CustomerToken();
        $token->customer_id = $this->customer_id;
        $token->token_value = CustomerToken::generateUniqueTokenString ();
        $token->token_status = CustomerToken::STATUS_ACTIVE;
        $token->save ();

        return $token;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByUnVerifiedTokenToken($token, $modelClass = "\api\models\CustomerToken") {

        $token = $modelClass::find()
            ->andWhere(['token_value' => $token])
            ->with('customer')
            ->one();

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save ();

        if ($token && $token->customer) {//&& !$token->customer->deleted
            return $token->customer;
        }

        //invalid token
        $token->delete ();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\api\models\CustomerToken")
    {
        $token = $modelClass::find ()->where ([
            'token_value' => $token,
            'token_status' => $modelClass::STATUS_ACTIVE
        ])
            ->with ('customer')
            ->one ();

        if (!$token)
            return false;

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save ();

        //should not able to login, if email not verified but have valid token

        if ($token->customer) {
            return $token->customer;
        }

        //invalid token
        $token->delete ();
    }

    /**
     * Whenever a user changes his password using any method (password reset email / profile page),
     * we need to send out the following email to confirm that his password was set
     */
    public function sendPasswordUpdatedEmail($restaurant_uuid = null)
    {
        \Yii::$app->mailer->htmlLayout = "layouts/text";

        if(!$restaurant_uuid) {
            $restaurant_uuid = $this->restaurant_uuid;
        }

        $restaurant = Restaurant::find()->andWhere(['restaurant_uuid' => $restaurant_uuid])->one();
 
        \Yii::$app->mailer->compose ([
            'html' => 'customer/password-updated-html',
            'text' => 'customer/password-updated-text',
        ], [
            'customer' => $this,
            'restaurant' => $restaurant
        ])
            ->setFrom ([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
            ->setTo ($this->customer_email)
            ->setSubject (Yii::t ('customer', 'Your '. $restaurant->restaurant.' password has been changed'))
            ->send();
    }

    /**
     * Verifies the customer email
     */
    public static function verifyEmail($email, $code) {

        $store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        $filter = $store_id ? [
            'AND',
            ['restaurant_uuid' => $store_id],
            [
                'OR',
                ['customer_new_email' => $email],
                ['customer_email' => $email]
            ]
        ]: [
                'OR',
                ['customer_new_email' => $email],
                ['customer_email' => $email]
        ];

        $model = self::find()
            ->andWhere($filter)
            ->one();

        if(!$model) {
            return [
                'success' => false,
                'message' =>Yii::t('api','This email verification link is no longer valid, please login to send a new one')
            ];
        }

        if ($model->customer_auth_key && $code && $model->customer_auth_key == $code) { //to cope with sql case insensitivity

            $model->setScenario(self::SCENARIO_VERIFY_EMAIL);

            //If not verified
            if ($model->customer_email_verification == Customer::EMAIL_NOT_VERIFIED) {
                //Verify this candidates email
                $model->customer_email_verification = Customer::EMAIL_VERIFIED;
            }

            // new email address

            if (!empty($model->customer_new_email)) {
                $model->customer_email = $model->customer_new_email;
                $model->customer_new_email = null;
            }

            $model->customer_auth_key = ''; //remove auth key

            if($model->save()) {
                return [
                    'success' => true,
                    'data' => $model
                ];
            }

            return [
                'success' => false,
                'message' => Yii::t('api','This email already registered!')
            ];

        } else {
            return [
                'success' => false,
                'message' => Yii::t('api','This email verification link is no longer valid, please login to send a new one')
            ];
        }
    }

    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail($restaurant_uuid = null) {

        $this->generateAuthKey();
 
        //Update customer's last email limit timestamp
        //$this->customer_limit_email = new Expression('NOW()');
        //$this->save(false);

        //to fix: password reset email on signup

        self::updateAll([
            'customer_auth_key' => $this->customer_auth_key,
            'customer_limit_email' => new Expression('NOW()')
        ], [
            "customer_id" => $this->customer_id
        ]);

        if ($this->customer_new_email) {
            $email = $this->customer_new_email;
        } else {
            $email = $this->customer_email;
        }

        if(!$restaurant_uuid) {
            $restaurant_uuid = $this->restaurant_uuid;
        }

        $restaurant = Restaurant::find()->andWhere(['restaurant_uuid' => $restaurant_uuid])->one();

        if($restaurant) {
            $verifyLink = $restaurant->restaurant_domain . '/verify-email/' . urlencode($email) . '/' 
                . $this->customer_auth_key;
        } else {
            $verifyLink = $verifyLink = Yii::$app->params['newDashboardAppUrl'] . '/verify-email/' . urlencode($email) . '/' 
                . $this->customer_auth_key;
        }
            
        $mailer = Yii::$app->mailer->compose([
                'html' => 'customer/verify-email-html',
                'text' => 'customer/verify-email-text',
            ], [
                'customer' => $this,
                'email' => $email,
                'restaurant' => $restaurant,
                'verifyLink' => $verifyLink
            ])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['appName']])
            ->setTo($email)
            ->setSubject('Please confirm your email address');

        try {
            return $mailer->send();
        } catch (\Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    public static function find() {
        return new query\CustomerQuery(get_called_class());
    }
}
