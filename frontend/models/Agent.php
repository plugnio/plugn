<?php

namespace frontend\models;

use Yii;

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
 * @property int|null $email_notification
 * @property string $agent_created_at
 * @property string $agent_updated_at
 *
 * @property AgentAssignment[] $agentAssignments
 */
class Agent extends \common\models\Agent {

    /**
     * Field for temporary password. If set, it will overwrite the old password on save
     * @var string
     */
    public $tempPassword;

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
        return array_merge(parent::rules(), [
            [['tempPassword'], 'required', 'on' => 'create'],
            [['tempPassword'], 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return array_merge(parent::rules(), [
            'tempPassword' => 'Password',
        ]);
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            // If tempPassword is set, save it as the new password for this user
            if ($this->tempPassword) {
                $this->setPassword($this->tempPassword);
            }

            return true;
        }
    }

}
