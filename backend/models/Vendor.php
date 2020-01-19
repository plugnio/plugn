<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "vendor".
 *
 * @property int $vendor_id
 * @property string $vendor_name
 * @property string $vendor_email
 * @property string $vendor_auth_key
 * @property string $vendor_password_hash
 * @property string|null $vendor_password_reset_token
 * @property int $vendor_status
 * @property string $vendor_created_at
 * @property string $vendor_updated_at
 *
 * @property Restaurant[] $restaurants
 */
class Vendor extends \common\models\Vendor implements IdentityInterface {

    /**
     * Field for temporary password. If set, it will overwrite the old password on save
     * @var string
     */
    public $tempPassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'vendor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword'], 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), ['tempPassword' => 'Password']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            // Generate Auth key if its a new vendor record
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
        switch ($this->vendor_status) {
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
        return static::findOne(['vendor_id' => $id, 'vendor_status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['vendor_email' => $email, 'vendor_status' => self::STATUS_ACTIVE]);
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
                    'vendor_password_reset_token' => $token,
                    'vendor_status' => self::STATUS_ACTIVE,
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
        return $this->vendor_auth_key;
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
        return Yii::$app->security->validatePassword($password, $this->vendor_password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->vendor_password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->vendor_auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->vendor_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->vendor_password_reset_token = null;
    }

}
