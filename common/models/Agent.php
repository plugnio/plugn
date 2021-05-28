<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use common\models\AgentToken;

/**
 * This is the model class for table "agent".
 *
 * @property int $agent_id
 * @property string $agent_name
 * @property string $agent_email
 * @property string $agent_auth_key
 * @property string $agent_password_hash
 * @property string|null $agent_password_reset_token
 * @property int $agent_status
 * @property int $email_notification
 * @property int $receive_weekly_stats
 * @property int $reminder_email
 * @property string $agent_created_at
 * @property string $agent_updated_at
 *
 * @property Restaurant[] $restaurantsManaged
 * @property Restaurant[] $restaurants
 * @property AgentAssignment[] $agentAssignments
 */
class Agent extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SCENARIO_CHANGE_PASSWORD = 'change-password';
    const SCENARIO_CREATE_NEW_AGENT = 'create';


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
    public static function tableName() {
        return 'agent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['agent_name', 'agent_email'], 'required'],
            ['tempPassword', 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE_NEW_AGENT]],
            [['agent_status','email_notification', 'reminder_email','receive_weekly_stats'], 'integer'],
            [['agent_created_at', 'agent_updated_at'], 'safe'],
            [['agent_name', 'agent_email', 'agent_password_hash', 'agent_password_reset_token'], 'string', 'max' => 255],
            [['agent_auth_key'], 'string', 'max' => 32],
            [['agent_email'], 'unique'],
            [['agent_email'], 'email'],
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword'], 'safe'],
            [['agent_password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'agent_id' => 'Agent ID',
            'agent_name' => 'Agent Name',
            'tempPassword' => 'Password',
            'agent_email' => 'Agent Email',
            'agent_auth_key' => 'Agent Auth Key',
            'agent_password_hash' => 'Agent Password Hash',
            'agent_password_reset_token' => 'Agent Password Reset Token',
            'agent_status' => 'Agent Status',
            'email_notification' => 'Email Notification',
            'agent_created_at' => 'Agent Created At',
            'agent_updated_at' => 'Agent Updated At',
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'agent_created_at',
                'updatedAtAttribute' => 'agent_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            // Generate Auth key if its a new agent record
            if ($insert) {
                $this->generateAuthKey();
            }

            // If tempPassword is set, save it as the new password for this user
            if ($this->tempPassword) {
                $this->setPassword($this->tempPassword);
            }

            return true;
        }
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus() {
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
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['agent_id' => $id, 'agent_status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $token = AgentToken::find()->where([
                    'token_value' => $token,
                    'token_status' => AgentToken::STATUS_ACTIVE
                ])
                ->with('agent')
                ->one();

        if (!$token)
            return false;

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save();

        //should not able to login, if email not verified but have valid token

        if ($token->agent) {
            return $token->agent;
        }

        //invalid token
        $token->delete();
    }

    /**
* Create an Access Token Record for this agent
* if the agent already has one, it will return it instead
* @return \common\models\AgentToken
*/
public function getAccessToken() {
   // Return existing inactive token if found
   $token = AgentToken::findOne([
               'agent_id' => $this->agent_id,
               'token_status' => AgentToken::STATUS_ACTIVE
   ]);

   if ($token) {
       return $token;
   }

   // Create new inactive token

   $token = new AgentToken();
   $token->agent_id = $this->agent_id;
   $token->token_value = AgentToken::generateUniqueTokenString();
   $token->token_status = AgentToken::STATUS_ACTIVE;
   $token->save();

   return $token;
}


    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['agent_email' => $email, 'agent_status' => self::STATUS_ACTIVE]);
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
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->agent_auth_key;
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
        return Yii::$app->security->validatePassword($password, $this->agent_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->agent_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->agent_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->agent_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->agent_password_reset_token = null;
    }

    /**
     *
     * @param type $restaurant_uuid
     * @return type
     */
    public function isOwner($storeUuid) {

      if($this->isOwner == null){

          $this->isOwner = AgentAssignment::find()
                            ->where(['agent_id' => Yii::$app->user->identity->agent_id, 'restaurant_uuid' => $storeUuid, 'role' => AgentAssignment::AGENT_ROLE_OWNER])
                            ->exists();
      }
        return $this->isOwner;
    }


    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['agent_auth_key']);
        unset($fields['agent_password_hash']);
        unset($fields['agent_password_reset_token']);

        return $fields;
    }



    /**
     * Get all Restaurant accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])
                ->via('agentAssignments');
    }

    /**
     * All assignment records made for this agent
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments()
    {
        return $this->hasMany(AgentAssignment::className(), ['agent_id' => 'agent_id']);
    }

}
