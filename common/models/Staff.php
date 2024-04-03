<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use common\models\StaffToken;

/**
 * This is the model class for table "staff".
 *
 * @property int $staff_id
 * @property string $staff_name
 * @property string $staff_email
 * @property string $staff_auth_key
 * @property string $staff_password_hash
 * @property string|null $staff_password_reset_token
 * @property int $staff_status
 * @property string $staff_created_at
 * @property string $staff_updated_at
 *
 */
class Staff extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SCENARIO_CHANGE_PASSWORD = 'change-password';
    const SCENARIO_CREATE_NEW = 'create';

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
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_name', 'staff_email'], 'required'],//'staff_password_hash'
            ['tempPassword', 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE_NEW]],
            [['staff_status'], 'integer'],
            [['staff_created_at', 'staff_updated_at'], 'safe'],
            [['staff_name', 'staff_email', 'staff_password_hash', 'staff_password_reset_token'], 'string', 'max' => 255],
            [['staff_auth_key'], 'string', 'max' => 32],
            [['staff_email'], 'unique'],
            [['staff_email'], 'email'],
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword'], 'safe'],
            [['staff_password_reset_token'], 'unique'],
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios ();

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => Yii::t('app','Staff ID'),
            'staff_name' => Yii::t('app','Staff Name'),
            'tempPassword' => Yii::t('app','Password'),
            'staff_email' => Yii::t('app','Staff Email'),
            'staff_auth_key' => Yii::t('app','Staff Auth Key'),
            'staff_password_hash' => Yii::t('app','Staff Password Hash'),
            'staff_password_reset_token' => Yii::t('app','Staff Password Reset Token'),
            'staff_status' => Yii::t('app','Staff Status'),
            'staff_created_at' => Yii::t('app','Staff Created At'),
            'staff_updated_at' => Yii::t('app','Staff Updated At')
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
                'createdAtAttribute' => 'staff_created_at',
                'updatedAtAttribute' => 'staff_updated_at',
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

            // Generate Auth key if its a new staff record
            if ($insert) {
                $this->generateAuthKey ();
            }

            // If tempPassword is set, save it as the new password for this user
            if ($this->tempPassword) {
                $this->setPassword ($this->tempPassword);
            }

            return true;
        }
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if (!$insert && $this->staff_password_hash && isset($changedAttributes['staff_password_hash'])) {
            $this->sendPasswordUpdatedEmail ();
        }
    }

    /**
     * Whenever a user changes his password using any method (password reset email / profile page),
     * we need to send out the following email to confirm that his password was set
     */
    public function sendPasswordUpdatedEmail()
    {
        $ml = new MailLog();
        $ml->to = $this->staff_email;
        $ml->from = \Yii::$app->params['noReplyEmail'];
        $ml->subject = 'Your '. \Yii::$app->params['appName'] .' password has been changed';
        $ml->save();

        \Yii::$app->mailer->htmlLayout = "layouts/text";

        $mailer = \Yii::$app->mailer->compose ([
            'html' => 'staff/password-updated-html',
            'text' => 'staff/password-updated-text',
        ], [
            'staff' => $this
        ])
            ->setFrom([\Yii::$app->params['noReplyEmail'] => \Yii::$app->name])
            ->setTo ($this->staff_email)
            ->setReplyTo(\Yii::$app->params['supportEmail'])
            ->setSubject (Yii::t ('staff', 'Your '. \Yii::$app->params['appName'] .' password has been changed'));

        $mailer->setHeader ("poolName", \Yii::$app->params['elasticMailIpPool']);

        $mailer->send ();
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus()
    {
        switch ($this->staff_status) {
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
        return static::findOne (['staff_email' => $email, 'staff_status' => self::STATUS_ACTIVE]);
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
            'staff_password_reset_token' => $token,
            'staff_status' => self::STATUS_ACTIVE,
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
        return $this->staff_auth_key;
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
        return Yii::$app->security->validatePassword ($password, $this->staff_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->staff_password_hash = Yii::$app->security->generatePasswordHash ($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->staff_auth_key = Yii::$app->security->generateRandomString ();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->staff_password_reset_token = Yii::$app->security->generateRandomString () . '_' . time ();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->staff_password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne (['staff_id' => $id, 'staff_status' => self::STATUS_ACTIVE]);
    }

    /**
     * Create an Access Token Record for this staff
     * if the staff already has one, it will return it instead
     * @return \common\models\StaffToken
     */
    public function getAccessToken()
    {
        // Return existing inactive token if found
        $token = \crm\models\StaffToken::findOne ([
            'staff_id' => $this->staff_id,
            'token_status' => StaffToken::STATUS_ACTIVE
        ]);

        if ($token) {
            return $token;
        }

        // Create new inactive token

        $token = new StaffToken();
        $token->staff_id = $this->staff_id;
        $token->token_value = StaffToken::generateUniqueTokenString ();
        $token->token_status = StaffToken::STATUS_ACTIVE;
        $token->save ();

        return $token;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\crm\models\StaffToken")
    {

        $token = $modelClass::find ()->where ([
            'token_value' => $token,
            'token_status' => $modelClass::STATUS_ACTIVE
        ])
            ->with ('staff')
            ->one ();

        if (!$token)
            return false;

        //update last used datetime

        $token->token_last_used_datetime = new Expression('NOW()');
        $token->save ();

        //should not able to login, if email not verified but have valid token

        if ($token->staff) {
            return $token->staff;
        }

        //invalid token
        $token->delete ();
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields ();

        // remove fields that contain sensitive information
        unset($fields['staff_auth_key']);
        unset($fields['staff_password_hash']);
        unset($fields['staff_password_reset_token']);

        return $fields;
    }

    /**
     * @return query\StaffQuery
     */
    public static function find()
    {
        return new query\StaffQuery(get_called_class());
    }
}
