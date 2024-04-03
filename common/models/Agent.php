<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;


/**
 * This is the model class for table "agent".
 *
 * @property int $agent_id
 * @property string utm_uuid
 * @property string $agent_name
 * @property string $agent_email
 * @property string $agent_number
 * @property string $agent_phone_country_code
 * @property string $agent_auth_key
 * @property string $agent_password_hash
 * @property string|null $agent_password_reset_token
 * @property int $agent_status
 * @property int $email_notification
 * @property int $receive_weekly_stats
 * @property int $reminder_email
 * @property string $agent_language_pref
 * @property string $last_active_at
 * @property string $agent_created_at
 * @property string $agent_updated_at
 * @property string $agent_deleted_at
 *
 * @property Restaurant[] $restaurantsManaged
 * @property Restaurant[] $restaurants
 * @property AgentAssignment[] $agentAssignments
 * @property Campaign $campaign
 */
class Agent extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

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

    /**
     * Field for temporary password. If set, it will overwrite the old password on save
     * @var string
     */
    public $isOwner = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent_name', 'agent_email'], 'required'],//'agent_password_hash'
            ['tempPassword', 'required', 'on' => [
                self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE_NEW_AGENT]],
            [['agent_status', 'email_notification', 'reminder_email', 'receive_weekly_stats'], 'integer'],
            [['agent_created_at', 'agent_updated_at', 'agent_deleted_at'], 'safe'],
            [['agent_phone_country_code', 'agent_number'], 'number'],
            [['agent_number'], 'unique', 'comboNotUnique' => 'Phone no. already exist.',  'targetAttribute' => ['agent_phone_country_code', 'agent_number', 'deleted']],
            [['agent_name', 'agent_email', 'agent_password_hash', 'agent_password_reset_token'], 'string', 'max' => 255],
            ['agent_language_pref', 'string', 'max' => 2],
            [['agent_auth_key'], 'string', 'max' => 32],
            [['agent_email'], 'unique'],
            [['agent_email'], 'email'],
            [['ip_address'], 'string', 'max' => 45],
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword', 'last_active_at'], 'safe'],
            [['agent_password_reset_token'], 'unique'],
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios ();

        $scenarios['updateLanguagePref'] = ['agent_language_pref', 'ip_address'];

        $scenarios['update-email'] = ['agent_email', 'agent_new_email', 'ip_address'];

        $scenarios['verify-email'] = ['agent_email', 'agent_new_email', 'agent_email_verification', 'agent_auth_key', 'ip_address'];

        $scenarios['SCENARIO_DELETE'] = ['deleted', 'ip_address', 'agent_deleted_at'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agent_id' => Yii::t('app','Agent ID'),
            'utm_uuid' => Yii::t('app','Campaign / Utm ID'),
            'agent_name' => Yii::t('app','Agent Name'),
            'tempPassword' => Yii::t('app','Password'),
            'agent_email' => Yii::t('app','Agent Email'),
            'agent_auth_key' => Yii::t('app','Agent Auth Key'),
            'agent_password_hash' => Yii::t('app','Agent Password Hash'),
            'agent_password_reset_token' => Yii::t('app','Agent Password Reset Token'),
            'agent_language_pref' => Yii::t('app','Agent Language Preference'),
            'agent_status' => Yii::t('app','Agent Status'),
            'email_notification' => Yii::t('app','Email Notification'),
            'agent_created_at' => Yii::t('app','Agent Created At'),
            'agent_updated_at' => Yii::t('app','Agent Updated At'),
            'agent_deleted_at' => Yii::t('app','Agent Deleted At'),
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'agent_created_at',
                'updatedAtAttribute' => 'agent_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave ($insert)) {

            // Generate Auth key if its a new agent record
            if ($insert) {
                $this->generateAuthKey ();
            }

            // If tempPassword is set, save it as the new password for this user
            if ($this->tempPassword) {
                $this->setPassword ($this->tempPassword);
            }

            if(Yii::$app->request instanceof \yii\web\Request) {
                
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

                if ($insert) {

                    $count = Agent::find()
                        ->andWhere(['ip_address' => $this->ip_address])
                        ->andWhere("DATE(agent_created_at) = DATE('".date('Y-m-d')."')")
                        ->count();

                    if ($count > 1) {
                        Yii::error("too may agent signup from same ip");

                        //block ip

                        $biModel = new BlockedIp();
                        $biModel->ip_address = $this->ip_address;
                        $biModel->note = "Too many agent signups from same ip";
                        $biModel->save(false);

                        return $this->addError('ip_address', "Too many requests");
                    }
                }
            }

            return true;
        }
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if (
            !$insert &&
            $this->agent_password_hash &&
            isset($changedAttributes['agent_password_hash'])
        ) {
            $this->sendPasswordUpdatedEmail ();
        }

        if($insert && $this->campaign) {
            $this->campaign->no_of_signups++;
            $this->campaign->save(false);
        }
    }

    /**
     * Whenever a user changes his password using any method (password reset email / profile page),
     * we need to send out the following email to confirm that his password was set
     */
    public function sendPasswordUpdatedEmail()
    {
        $ml = new MailLog();
        $ml->to = $this->agent_email;
        $ml->from = \Yii::$app->params['noReplyEmail'];
        $ml->subject = 'Your '. \Yii::$app->params['appName'] .' password has been changed';
        $ml->save();

        \Yii::$app->mailer->htmlLayout = "layouts/text";

        $mailer = \Yii::$app->mailer->compose ([
                'html' => 'agent/password-updated-html',
                'text' => 'agent/password-updated-text',
            ], [
                'agent' => $this
            ])
            ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
            ->setReplyTo(\Yii::$app->params['supportEmail'])
            ->setTo ($this->agent_email)
            ->setSubject (Yii::t ('agent', 'Your '. \Yii::$app->params['appName'] .' password has been changed'));

        if(\Yii::$app->params['elasticMailIpPool'])
            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

        $mailer->send ();
    }

    /**
     * Verifies the agent email
     */
    public static function verifyEmail($email, $code) {

        $model = self::find()
            ->andWhere(['deleted' => 0])
            ->andWhere([
                'OR',
                ['agent_new_email' => $email],
                ['agent_email' => $email]
            ])
            ->one();

        if(!$model) {
            return [
                'success' => false,
                'message' => Yii::t('agent','This email verification link is no longer valid, please login to send a new one')
            ];
        }

        if ($model->agent_auth_key && $code && $model->agent_auth_key == $code) { //to cope with sql case insensitivity

            $model->setScenario(self::SCENARIO_VERIFY_EMAIL);

            //If not verified
            if ($model->agent_email_verification == Agent::EMAIL_NOT_VERIFIED) {
                //Verify this candidates email
                $model->agent_email_verification = Agent::EMAIL_VERIFIED;
            }

            // new email address

            if (!empty($model->agent_new_email)) {
                $model->agent_email = $model->agent_new_email;
                $model->agent_new_email = null;
            }

            $model->agent_auth_key = ''; //remove auth key

            if($model->save()) {

                return [
                    'success' => true,
                    'data' => $model
                ];
            }

            return [
                'success' => false,
                'message' => Yii::t('agent','This email already registered!')
            ];

        } else {
            return [
                'success' => false,
                'message' => Yii::t('agent','This email verification link is no longer valid, please login to send a new one')
            ];
        }
    }

    /**
     * Sends an email requesting a user to verify his email address
     * @return boolean whether the email was sent
     */
    public function sendVerificationEmail() {

        $this->generateAuthKey();

        //Update agent's last email limit timestamp
        //$this->agent_limit_email = new Expression('NOW()');
        //$this->save(false);

        //to fix: password reset email on signup

        self::updateAll([
            'agent_auth_key' => $this->agent_auth_key,
            'agent_limit_email' => new Expression('NOW()')
        ], [
            "agent_id" => $this->agent_id
        ]);

        if ($this->agent_new_email) {
            $email = $this->agent_new_email;
        } else {
            $email = $this->agent_email;
        }

        $ml = new MailLog();
        $ml->to = $email;
        $ml->from = \Yii::$app->params['noReplyEmail'];
        $ml->subject = 'Please confirm your email address';
        $ml->save();

        $mailer = Yii::$app->mailer->compose([
            'html' => 'agent/verify-email-html',
            'text' => 'agent/verify-email-text',
        ], [
            'agent' => $this,
            'email' => $email
        ])
            ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
            ->setReplyTo(\Yii::$app->params['supportEmail'])
            ->setTo($email)
            ->setSubject('Please confirm your email address');

        if(\Yii::$app->params['elasticMailIpPool'])
            $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

        try {
            return $mailer->send();
        } catch (\Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus()
    {
        switch ($this->agent_status) {
            case self::STATUS_ACTIVE:
                return "Active";
                break;
            case self::STATUS_DELETED:
                return "Deleted";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne ([
            'agent_email' => $email,
            'agent_status' => self::STATUS_ACTIVE,
            'deleted' => 0
        ]);
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
            'agent_password_reset_token' => $token,
            'agent_status' => self::STATUS_ACTIVE,
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
        return $this->agent_auth_key;
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
        return Yii::$app->security->validatePassword ($password, $this->agent_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->agent_password_hash = Yii::$app->security->generatePasswordHash ($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->agent_auth_key = Yii::$app->security->generateRandomString ();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->agent_password_reset_token = Yii::$app->security->generateRandomString () . '_' . time ();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->agent_password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne ([
            'agent_id' => $id,
            'agent_email_verification' => true,
            'agent_status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * Create an Access Token Record for this agent
     * if the agent already has one, it will return it instead
     * @return \common\models\AgentToken
     */
    public function getAccessToken()
    {
        // Return existing inactive token if found
        $token = \agent\models\AgentToken::findOne ([
            'agent_id' => $this->agent_id,
            'token_status' => AgentToken::STATUS_ACTIVE
        ]);

        if ($token) {
            return $token;
        }

        // Create new inactive token

        $token = new AgentToken();
        $token->agent_id = $this->agent_id;
        $token->token_value = AgentToken::generateUniqueTokenString ();
        $token->token_status = AgentToken::STATUS_ACTIVE;
        $token->save ();

        return $token;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByUnVerifiedTokenToken($token, $modelClass = "\agent\models\AgentToken") {

        $token = $modelClass::find()
            ->andWhere(['token_value' => $token])
            ->with('agent')
            ->one();

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save ();

        if ($token && $token->agent) {//&& !$token->agent->deleted
            return $token->agent;
        }

        //invalid token
        $token->delete ();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\agent\models\AgentToken")
    {
        $token = $modelClass::find ()->where ([
            'token_value' => $token,
            'token_status' => $modelClass::STATUS_ACTIVE
        ])
            ->with ('agent')
            ->one ();

        if (!$token)
            return false;

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save ();

        //should not able to login, if email not verified but have valid token

        //'agent_email_verification' => true,
        //

        if ($token->agent && $token->agent->agent_email_verification) {
            return $token->agent;
        }

        //invalid token
        $token->delete ();
    }

    /**
     *
     * @param type $restaurant_uuid
     * @return type
     */
    public function isOwner($storeUuid)
    {
        if ($this->isOwner == null) {

            $this->isOwner = AgentAssignment::find ()
                ->andWhere ([
                    'agent_id' => Yii::$app->user->identity->agent_id,
                    'restaurant_uuid' => $storeUuid,
                    'role' => AgentAssignment::AGENT_ROLE_OWNER
                ])
                ->exists ();
        }

        return $this->isOwner;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields ();

        // remove fields that contain sensitive information
        unset($fields['agent_auth_key']);
        unset($fields['agent_password_hash']);
        unset($fields['agent_password_reset_token']);

        return $fields;
    }

    /**
     * default values for new store
     * @param $store
     * @return array|string[]
     */
    public function afterSignUp($store)
    {
        //Create a catrgory for a store by default named "Products". so they can get started adding products without having to add category first
        $category_model = new Category();
        $category_model->restaurant_uuid = $store->restaurant_uuid;
        $category_model->title = 'Products';
        $category_model->title_ar = 'منتجات';

        if (!$category_model->save ()) {
            return [
                "operation" => "error",
                "message" => $category_model->errors
            ];
        }

        //Create a business Location for a store by default named "Main Branch".
        $business_location_model = new BusinessLocation();
        $business_location_model->restaurant_uuid = $store->restaurant_uuid;
        $business_location_model->country_id = $store->country_id;
        $business_location_model->support_pick_up = 1;
        $business_location_model->business_location_name = 'Main Branch';
        $business_location_model->business_location_name_ar = 'الفرع الرئيسي';
        if (!$business_location_model->save ()) {
            return [
                "operation" => "error",
                "message" => $business_location_model->errors
            ];
        }

        //Enable cash by default
        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = 3; //Cash
        $payments_method->restaurant_uuid = $store->restaurant_uuid;
        if (!$payments_method->save ()) {
            return [
                "operation" => "error",
                "message" => $payments_method->errors
            ];
        }

        $assignment_agent_model = new AgentAssignment();
        $assignment_agent_model->agent_id = $this->agent_id;
        $assignment_agent_model->assignment_agent_email = $this->agent_email;
        $assignment_agent_model->role = AgentAssignment::AGENT_ROLE_OWNER;
        $assignment_agent_model->restaurant_uuid = $store->restaurant_uuid;
        if (!$assignment_agent_model->save ()) {
            return [
                "operation" => "error",
                "message" => $assignment_agent_model->errors
            ];
        }

        \Yii::info ("[New Store Signup] " . $store->name . " has just joined Plugn", __METHOD__);

            $full_name = explode (' ', $this->agent_name);
            $firstname = $full_name[0];
            $lastname = array_key_exists (1, $full_name) ? $full_name[1] : null;

            Yii::$app->eventManager->track('Store Created', [
                    'first_name' => trim ($firstname),
                    'last_name' => trim ($lastname),
                    'store_name' => $store->name,
                    'phone_number' => $store->owner_number,
                    'email' => $this->agent_email,
                    'store_url' => $store->restaurant_domain,
                    "country" => $store->country? $store->country->country_name: null,
                    "campaign" => $store->sourceCampaign ? $store->sourceCampaign->utm_campaign: null,
                    "utm_medium" => $store->sourceCampaign ? $store->sourceCampaign->utm_medium: null,
                ], 
                null, 
                $store->restaurant_uuid
            );

            /**
             * 
            Yii::$app->eventManager->track('Agent Signup', [
                'first_name' => trim ($firstname),
                'last_name' => trim ($lastname),
                'store_name' => $store->name,
                'phone_number' => $store->owner_number,
                'email' => $this->agent_email,
                'store_url' => $store->restaurant_domain,
                "country" => $store->country? $store->country->country_name: null,
                "campaign" => $store->sourceCampaign ? $store->sourceCampaign->utm_campaign: null,
                "utm_medium" => $store->sourceCampaign ? $store->sourceCampaign->utm_medium: null,
            ],
                null,
                $this->agent_id
            );*/

        return [
            'operation' => 'success'
        ];
    }

    /**
     * Get all Restaurant accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany ($modelClass::className (), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via ('agentAssignments')
            ->andWhere(['is_deleted' => false]);
    }

    /**
     * All assignment records made for this agent
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments($modelClass = "\common\models\AgentAssignment")
    {
        return $this->hasMany ($modelClass::className (), ['agent_id' => 'agent_id'])
            ->joinWith(['restaurant'])
            ->andWhere(['restaurant.is_deleted' => false]);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getCampaign($modelClass = "\common\models\Campaign")
    {
        return $this->hasOne ($modelClass::className (), ['utm_uuid' => 'utm_uuid']);
    }

    /**
     * @return query\RestaurantQuery
     */
    public static function find()
    {
        return new query\AgentQuery(get_called_class());
    }

}
