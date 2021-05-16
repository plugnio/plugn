<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "agent_assignment".
 *
 * @property integer $assignment_id
 * @property integer $restaurant_uuid
 * @property integer $agent_id
 * @property integer $role
 * @property string $assignment_agent_email
 * @property string $assignment_created_at
 * @property string $assignment_updated_at
 * @property int $email_notification
 * @property int $receive_weekly_stats
 * @property int $reminder_email
 *
 * @property Agent $agent
 * @property Restaurant $restaurant
 */
class AgentAssignment extends \yii\db\ActiveRecord {

    //Values for `role`
    const AGENT_ROLE_OWNER = 1;
    const AGENT_ROLE_STAFF = 2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'agent_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['assignment_agent_email'], 'required'],
            [['role'], 'required'],
            [['assignment_agent_email'], 'string', 'max' => 255],
            [['assignment_agent_email'], 'email'],
            ['role', 'in', 'range' => [self::AGENT_ROLE_OWNER, self::AGENT_ROLE_STAFF]],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['agent_id','email_notification', 'reminder_email','receive_weekly_stats'], 'integer'],
            //Only allow one record of each email per account
//            ['assignment_agent_email', 'unique', 'filter' => function($query) {
//                    $query->where(['agent_id' => $this->agent_id, 'restaurant_uuid' => $this->restaurant_uuid]);
//                }, 'message' => 'This person has already been added as an agent.'],
//
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'assignment_created_at',
                'updatedAtAttribute' => 'assignment_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert) {

        if ($insert) {
            if ($agent_model = Agent::findByEmail($this->assignment_agent_email))
                $this->agent_id = $agent_model->agent_id;
            else
                return $this->addError('assignment_agent_email', 'Could not find a Plugn account matching ' . $this->assignment_agent_email);

            if (AgentAssignment::find()->where(['agent_id' => $this->agent_id, 'restaurant_uuid' => $this->restaurant_uuid])->exists())
                return $this->addError('assignment_agent_email', 'This person has already been added as an agent.' . $this->assignment_agent_email);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'assignment_id' => 'Assignment ID',
            'restaurant_uuid' => 'Restaurant UUID',
            'agent_id' => 'Agent ID',
            'role' => "Role",
            'email_notification' => 'Email Notification',
            'assignment_agent_email' => 'Email',
            'assignment_created_at' => 'Date Assigned',
            'assignment_updated_at' => 'Assignment Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent() {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
