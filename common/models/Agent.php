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
            [['agent_status','email_notification'], 'integer'],
            [['agent_created_at', 'agent_updated_at'], 'safe'],
            [['agent_name', 'agent_email', 'agent_password_hash', 'agent_password_reset_token'], 'string', 'max' => 255],
            [['agent_auth_key'], 'string', 'max' => 32],
            [['agent_email'], 'unique'],
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
    /*
     * Start Identity Code
     */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['agent_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token_value, $unVerifiedToken = false) {
        $token = AgentToken::find()
                ->where([
                    'token_value' => $token_value,
                    'token_status' => AgentToken::STATUS_ACTIVE
                ])
                ->with('agent')
                ->one();

        if (!$token)
            return false;

        //update last used datetime 

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save();

 
        // invalid token 

        $token->delete();
    }

    
    
     /**
     * Create an Access Token Record for this employer
     * if the employer already has one, it will return it instead
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
     * Finds agent by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['agent_email' => $email]);
    }

    /**
     * Finds agent by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

  

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->agent_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password) {
        $this->agent_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = strtoupper($this->generateUniqueRandomString('auth_key', 4));
    }

    /**
     * Generate unique string for a given attribute of given length
     * @param $attribute
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateUniqueRandomString($attribute, $length = 32) {
        $randomString = Yii::$app->getSecurity()->generateRandomString($length);

        if (!$this->findOne([$attribute => $randomString]))
            return $randomString;
        else
            return $this->generateUniqueRandomString($attribute, $length);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString(6) . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }


    
    /**
     * Get all Restaurant accounts this agent owns
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsOwned()
    {
        return $this->hasMany(Restaurant::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * Get all RestauranZ accounts this agent is assigned to manage
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
