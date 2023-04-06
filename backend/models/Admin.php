<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property int $admin_id
 * @property string $admin_name
 * @property string $admin_email
 * @property string $admin_auth_key
 * @property string $admin_password_hash
 * @property string $admin_password_reset_token
 * @property int $admin_status
 * @property int $admin_created_at
 * @property int $admin_updated_at
 * @property string $password write-only password
 */
class Admin extends \common\models\Admin
{
    /**
     * Field for temporary password. If set, it will overwrite the old password on save
     * @var string
     */
    public $tempPassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword'], 'safe']
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'tempPassword' => 'Password'
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        // If tempPassword is set, save it as the new password for this user
        if($this->tempPassword) {
            $this->setPassword($this->tempPassword);
        }

        return true;
    }
}
