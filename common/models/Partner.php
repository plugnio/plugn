<?php

namespace common\models;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use common\models\PartnerToken;
/**
 * This is the model class for table "partner".
 *
 * @property string $partner_uuid
 * @property string $username
 * @property string $partner_auth_key
 * @property string $partner_password_hash
 * @property string|null $password_reset_token
 * @property string $partner_email
 * @property string $referral_code
 * @property string $partner_iban
 * @property string $benef_name
 * @property integer $bank_id
 * @property int $commission
 * @property int $partner_status
 * @property int $created_at
 * @property int $updated_at
 * @property int $partner_phone_number_country_code
 * @property string $partner_phone_number
 *
 * @property PartnerPayout[] $partnerPayouts
 * @property PartnerToken[] $partnerTokens
 * @property Store[] $stores
 */

class Partner extends \yii\db\ActiveRecord implements IdentityInterface {

   const STATUS_DELETED = 0;
   const STATUS_ACTIVE = 10;

   const SCENARIO_CHANGE_PASSWORD = 'change-password';
   const SCENARIO_CREATE_NEW_PARTNER = 'create';
    const SCENARIO_PASSWORD_TOKEN = 'password-token';

   /**
    * Field for temporary password. If set, it will overwrite the old password on save
    * @var string
    */
   public $tempPassword;

   /**
    * {@inheritdoc}
    */
   public static function tableName() {
       return 'partner';
   }

   /**
    * {@inheritdoc}
    */
   public function rules() {
       return [
           [['username', 'partner_email','partner_iban','benef_name','bank_id','partner_phone_number', 'partner_password_hash', 'referral_code'], 'required'],
           [['partner_status'], 'integer'],
           ['partner_status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
           ['tempPassword', 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE_NEW_PARTNER]],
           [['commission'], 'number'],
           [['partner_email'], 'email'],
           [['partner_uuid'], 'string', 'max' => 60],
           [['partner_iban'], 'string', 'max' => 100],
           [['username', 'partner_password_hash', 'partner_password_reset_token', 'partner_email','benef_name'], 'string', 'max' => 255],
           [['partner_auth_key'], 'string', 'max' => 32],
           [['referral_code'], 'string', 'max' => 6],
           [['username'], 'unique'],
           [['partner_email'], 'unique'],
           [['partner_created_at', 'partner_updated_at'], 'safe'],
           [['partner_password_reset_token'], 'unique'],
           [['partner_uuid'], 'unique'],
           [['tempPassword'], 'required', 'on' => 'create'],
           [['tempPassword'], 'safe'],

           [['partner_phone_number_country_code'], 'integer'],
           [['partner_phone_number'], 'string', 'min' => 6, 'max' => 20],
           [['partner_phone_number'], PhoneInputValidator::className(), 'message' => 'Please insert a valid phone number'],

           [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
       ];
   }


   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
       return [
           'partner_uuid' => Yii::t('app','Partner Uuid'),
           'username' => Yii::t('app','Username'),
           'partner_auth_key' => Yii::t('app','Auth Key'),
           'partner_password_hash' => Yii::t('app','Password Hash'),
           'partner_password_reset_token' => Yii::t('app','Password Reset Token'),
           'partner_email' => Yii::t('app','Email'),
           'partner_status' => Yii::t('app','partner_status'),
           'created_at' => Yii::t('app','Created At'),
           'updated_at' => Yii::t('app','Updated At'),
           'referral_code' => Yii::t('app','Referral Code'),
           'benef_name' => Yii::t('app','Beneficiary Name'),
           'bank_id' => Yii::t('app','Bank ID')
       ];
   }

   /**
    *
    * @return type
    */
   public function behaviors() {
       return [
           [
               'class' => AttributeBehavior::className(),
               'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'partner_uuid',
               ],
               'value' => function() {
                   if (!$this->partner_uuid)
                       $this->partner_uuid = 'par_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                   return $this->partner_uuid;
               }
           ],
           [
               'class' => AttributeBehavior::className(),
               'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'referral_code',
               ],
               'value' => function() {
                   if (!$this->referral_code) {
                       // Get a unique uuid from partner table
                       $this->referral_code = strtoupper(static::getUniqueReferralCode());
                   }

                   return $this->referral_code;
               }
           ],
           [
               'class' => TimestampBehavior::className(),
               'createdAtAttribute' => 'partner_created_at',
               'updatedAtAttribute' => 'partner_updated_at',
               'value' => new Expression('NOW()'),
           ],
            [
                'class' => \borales\extensions\phoneInput\PhoneInputBehavior::className (),
                // 'attributes' => [
                //           ActiveRecord::EVENT_BEFORE_INSERT => ['phone_number_country_code', 'phone_number'],
                //       ],
                'countryCodeAttribute' => 'partner_phone_number_country_code',
                'phoneAttribute' => 'partner_phone_number',
            ]

       ];
   }


   /**
    * Get a unique alphanumeric uuid to be used for a referral_code
    * @return string uuid
    */
   private static function getUniqueReferralCode($length = 6) {
       $uuid = \ShortCode\Random::get($length);

       $isNotUnique = static::find()->where(['referral_code' => $uuid])->exists();

       // If not unique, try again recursively
       if ($isNotUnique) {
           return static::getUniqueReferralCode($length);
       }

       return $uuid;
   }

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['partner_auth_key']);
        unset($fields['partner_password_hash']);
        unset($fields['partner_password_reset_token']);

        return $fields;
    }

   /**
    * {@inheritdoc}
    */
   public function beforeSave($insert) {
       if (parent::beforeSave($insert)) {

           // Generate Auth key if its a new partner record
           if ($insert) {
               $this->generateAuthKey();
           }

           // If tempPassword is set, save it as the new password for this user
           if ($this->scenario == self::SCENARIO_CREATE_NEW_PARTNER && $this->tempPassword) {
               $this->setPassword($this->tempPassword);
           }


           return true;
       }
   }

    /**
     * @return array|array[]|\string[][]
     */
   public function scenarios()
   {
       return array_merge(parent::scenarios(), [
           self::SCENARIO_PASSWORD_TOKEN => ['partner_password_reset_token']
       ]);
   }


    public function afterSave($insert, $changedAttributes) {

       parent::afterSave($insert, $changedAttributes);

       if (
          !$insert &&
           array_key_exists('partner_iban', $changedAttributes) ||
           array_key_exists('benef_name', $changedAttributes) ||
           array_key_exists('bank_id', $changedAttributes)
       ) {

               PartnerPayout::updateAll([
                 'bank_id' => $this->bank_id,
                 'transfer_benef_name' => $this->benef_name,
                 'transfer_benef_iban' => $this->partner_iban
             ], [
                 'payout_status' => PartnerPayout::PAYOUT_STATUS_UNPAID,
                 'partner_uuid' => $this->partner_uuid
             ]);
       }

       return true;

    }

   /**
    * Returns String value of current status
    * @return string
    */
   public function getStatus() {
       switch ($this->partner_status) {
           case self::STATUS_ACTIVE:
               return "Active";
               break;
           case self::STATUS_DELETED:
               return "Deleted";
               break;
       }

       return "Couldnt find a status";
   }


   public function getTotalEarnings() {

     $totalEarningsFromOrders = $this->getTotalEarningsFromOrders() ? $this->getTotalEarningsFromOrders() : 0;
     $totalEarningsFromSubscriptions = $this->getTotalEarningsFromSubscriptions() ? $this->getTotalEarningsFromSubscriptions() : 0;

     $totalEarnings = $totalEarningsFromOrders + $totalEarningsFromSubscriptions;

       return $totalEarnings;
   }


   public function getPendingPayouts() {

     $totalPendingPayoutsFromOrders = $this->getPendingPayoutsFromOrders() ? $this->getPendingPayoutsFromOrders() : 0;


     $totalPendingPayoutsFromSubscriptions = $this->getPendingPayoutsFromSubscriptions() ? $this->getPendingPayoutsFromSubscriptions() : 0;

     $totalPendingPayouts = $totalPendingPayoutsFromOrders + $totalPendingPayoutsFromSubscriptions;

       return $totalPendingPayouts;
   }


   /**
   * Send new password to partner
   * @param Partner $model
   * @param $password
   * @return bool
   */
  public static function passwordMail($model, $password)
  {
      $ml = new MailLog();
      $ml->to = $model->partner_email;
      $ml->from = \Yii::$app->params['noReplyEmail'];
      $ml->subject = 'Your account password has been reset';
      $ml->save();

      $mailer = \Yii::$app->mailer->compose([
                   'html' => 'staff-password',
                       ], [
                         "model" => $model,
                         "password" => $password
               ])
               ->setFrom([\Yii::$app->params['noReplyEmail'] => 'Plugn'])
               ->setTo([$model->partner_email])
               ->setBcc(\Yii::$app->params['supportEmail'])
               ->setSubject('Your account password has been reset');

      try {
          return $mailer->send();
      } catch (\Swift_TransportException $e) {
          Yii::error($e->getMessage(), "email");
      }
  }



   /**
    * {@inheritdoc}
    */
   public static function findIdentity($id) {
       return static::findOne(['partner_uuid' => $id, 'partner_status' => self::STATUS_ACTIVE]);
   }

   /**
    * @inheritdoc
    */
   public static function findIdentityByAccessToken($token, $type = null) {
       $token = PartnerToken::find()->where([
                   'token_value' => $token,
                   'token_status' => PartnerToken::STATUS_ACTIVE
               ])
               ->with('partner')
               ->one();

       if (!$token)
           return false;

       //update last used datetime

       $token->token_last_used_datetime = new Expression('NOW()');
       $token->save();

       //should not able to login, if email not verified but have valid token

       if ($token->partner) {
           return $token->partner;
       }

       //invalid token
       $token->delete();
   }

   /**
    * Create an Access Token Record for this partner
    * if the partner already has one, it will return it instead
    * @return \common\models\PartnerToken
    */
    public function getAccessToken() {
      // Return existing inactive token if found
      $token = PartnerToken::findOne([
                  'partner_uuid' => $this->partner_uuid,
                  'token_status' => PartnerToken::STATUS_ACTIVE
      ]);

      if ($token) {
          return $token;
      }

      // Create new inactive token

      $token = new PartnerToken();
      $token->partner_uuid = $this->partner_uuid;
      $token->token_value = PartnerToken::generateUniqueTokenString();
      $token->token_status = PartnerToken::STATUS_ACTIVE;
      $token->save();

      return $token;
    }


   /**
    * Finds user by email
    *
    * @param string $email
    * @return static|null
    */
   public static function findByEmail($email) {
       return static::findOne(['partner_email' => $email, 'partner_status' => self::STATUS_ACTIVE]);
   }

   /**
    * Finds partner ReferralCode
    *
    * @param string $email
    * @return static|null
    */
   public static function findByReferralCode($referralCode) {
       return static::findOne(['referral_code' => $referralCode, 'partner_status' => self::STATUS_ACTIVE]);
   }

   /**
    * Finds user by password reset token
    *
    * @param string $token password reset token
    * @return static|null
    */
   public static function findByPasswordResetToken($token) {
       if (!static::isPasswordResetTokenValid($token)) {
           return null;
       }

       return static::findOne([
                   'partner_password_reset_token' => $token,
                   'partner_status' => self::STATUS_ACTIVE,
       ]);
   }

   /**
    * Finds out if password reset token is valid
    *
    * @param string $token password reset token
    * @return bool
    */
   public static function isPasswordResetTokenValid($token) {
       if (empty($token)) {
           return false;
       }

       $timestamp = (int) substr($token, strrpos($token, '_') + 1);
       $expire = Yii::$app->params['user.passwordResetTokenExpire'];
       return $timestamp + $expire >= time();
   }

   /**
    * {@inheritdoc}
    */
   public function getId() {
       return $this->partner_uuid;
   }

   /**
    * {@inheritdoc}
    */
   public function getAuthKey() {
       return $this->partner_auth_key;
   }

   /**
    * {@inheritdoc}
    */
   public function validateAuthKey($authKey) {
       return $this->getAuthKey() === $authKey;
   }

   /**
    * Validates password
    *
    * @param string $password password to validate
    * @return bool if password provided is valid for current user
    */
   public function validatePassword($password) {
       return Yii::$app->security->validatePassword($password, $this->partner_password_hash);
   }

   /**
    * Generates password hash from password and sets it to the model
    *
    * @param string $password
    */
   public function setPassword($password) {
       $this->partner_password_hash = Yii::$app->security->generatePasswordHash($password);
   }

   /**
    * Generates "remember me" authentication key
    */
   public function generateAuthKey() {
       $this->partner_auth_key = Yii::$app->security->generateRandomString();
   }

   /**
    * Generates new password reset token
    */
   public function generatePasswordResetToken() {
       $this->partner_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
   }

   /**
    * Removes password reset token
    */
   public function removePasswordResetToken() {
       $this->partner_password_reset_token = null;
   }


   /**
     * Gets query for [[PartnerPayouts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerPayouts()
    {
        return $this->hasMany(PartnerPayout::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[PartnerTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerTokens()
    {
        return $this->hasMany(PartnerToken::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
    }


    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Restaurant::className(), ['referral_code' => 'referral_code']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders() {
        return $this->hasMany(Order::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->filterWhere (['NOT IN', 'order.order_status', [Order::STATUS_ABANDONED_CHECKOUT, Order::STATUS_DRAFT,Order::STATUS_CANCELED,Order::STATUS_REFUNDED,Order::STATUS_PARTIALLY_REFUNDED]])
            ->via('stores');
    }




        /**
       * Gets query for [[Payments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getPayments()
      {
          return $this->hasMany(Payment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->via('activeOrders')
                      ->joinWith('order')
                      ->filterWhere (['NOT IN', 'order.order_status', [Order::STATUS_ABANDONED_CHECKOUT, Order::STATUS_DRAFT,Order::STATUS_CANCELED,Order::STATUS_REFUNDED,Order::STATUS_PARTIALLY_REFUNDED]])
                      ->andWhere(['payment.payment_current_status' => 'CAPTURED'])
                      ->andWhere(['>','payment.partner_fee' ,0]);
      }

        /**
       * Gets query for [[getSubscriptionPayments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getSubscriptionPayments()
      {
          return $this->hasMany(SubscriptionPayment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->andWhere(['>','subscription_payment.partner_fee' ,0])
                      ->andWhere(['subscription_payment.payment_current_status' => 'CAPTURED'])
                      ->via('stores');
      }

        /**
       * Gets query for [[Payments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getPendingPayoutsFromOrders()
      {
          return $this->hasMany(Payment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->via('activeOrders')
                      ->joinWith(['order','partner'])
                      ->andWhere(['payment.partner_payout_uuid' => null])
                      ->andWhere (new Expression('DATE(payment.payment_created_at) >= DATE(partner.partner_created_at) '))
                      ->sum('payment.partner_fee');

      }

      /**
       * Gets query for [[Payments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getPendingPayoutsFromSubscriptions()
      {
          return $this->hasMany(SubscriptionPayment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->via('stores')
                      ->joinWith(['restaurant','restaurant.partner'])
                      ->andWhere(['subscription_payment.partner_payout_uuid' => null])
                      ->andWhere (new Expression('DATE(subscription_payment.payment_created_at) >= DATE(partner.partner_created_at) '))
                      ->sum('subscription_payment.partner_fee');
      }

        /**
       * Gets query for [[Payments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getTotalEarningsFromOrders()
      {
          return $this->hasMany(Payment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->via('activeOrders')
                      ->joinWith('order')
                      ->filterWhere (['NOT IN', 'order.order_status', [Order::STATUS_ABANDONED_CHECKOUT, Order::STATUS_DRAFT,Order::STATUS_CANCELED,Order::STATUS_REFUNDED,Order::STATUS_PARTIALLY_REFUNDED]])
                      ->andWhere(['IN', 'payment.payout_status', [Payment::PAYOUT_STATUS_UNPAID, Payment::PAYOUT_STATUS_PENDING]])
                      ->andWhere(['not', ['payment.partner_payout_uuid' => null]])
                      ->andWhere(['payment.payment_current_status' => 'CAPTURED'])
                      ->sum('payment.partner_fee');
      }



        /**
       * Gets query for [[Payments]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getTotalEarningsFromSubscriptions()
      {
          return $this->hasMany(SubscriptionPayment::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                      ->via('stores')
                      ->andWhere(['IN', 'subscription_payment.payout_status', [SubscriptionPayment::PAYOUT_STATUS_UNPAID, SubscriptionPayment::PAYOUT_STATUS_PENDING]])
                      ->andWhere(['not', ['subscription_payment.partner_payout_uuid' => null]])
                      ->andWhere(['subscription_payment.payment_current_status' => 'CAPTURED'])
                      ->sum('subscription_payment.partner_fee');
      }

}
