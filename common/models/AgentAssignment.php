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
 * @property int $business_location_id
 * @property integer $role
 * @property string $assignment_agent_email
 * @property string $assignment_created_at
 * @property string $assignment_updated_at
 * @property int $email_notification
 * @property int $receive_weekly_stats
 * @property int $reminder_email
 *
 * @property Agent $agent
 * @property BusinessLocation $businessLocation
 * @property Restaurant $restaurant
 */
class AgentAssignment extends \yii\db\ActiveRecord {

    //Values for `role`
    const AGENT_ROLE_OWNER = 1;
    const AGENT_ROLE_STAFF = 2;
    const AGENT_ROLE_BRANCH_MANAGER = 3;

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
            [['role', 'agent_id', 'restaurant_uuid'], 'required'],
            [['business_location_id'], 'required','when' => function($model) {
                    return ($model->role == self::AGENT_ROLE_BRANCH_MANAGER);
                }
            ],
            [['assignment_agent_email'], 'string', 'max' => 255],
            [['assignment_agent_email'], 'email'],
            ['role', 'in', 'range' => [self::AGENT_ROLE_OWNER, self::AGENT_ROLE_STAFF, self::AGENT_ROLE_BRANCH_MANAGER]],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['agent_id','email_notification', 'reminder_email','receive_weekly_stats','business_location_id'], 'integer'],
            //Only allow one record of each email per account
//            ['assignment_agent_email', 'unique', 'filter' => function($query) {
//                    $query->where(['agent_id' => $this->agent_id, 'restaurant_uuid' => $this->restaurant_uuid]);
//                }, 'message' => 'This person has already been added as an agent.'],
//
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['agent_id' => 'agent_id']],
            [['business_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessLocation::className(), 'targetAttribute' => ['business_location_id' => 'business_location_id']],
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
                return $this->addError('assignment_agent_email', Yii::t('app', 'Could not find a Plugn account matching {email}', [
                    'email' => $this->assignment_agent_email
                ]));

            if (AgentAssignment::find()->where(['agent_id' => $this->agent_id, 'restaurant_uuid' => $this->restaurant_uuid])->exists())
                return $this->addError('assignment_agent_email', Yii::t('app', 'This person has already been added as an agent.'));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'assignment_id' => Yii::t('app','Assignment ID'),
            'restaurant_uuid' =>Yii::t('app', 'Restaurant UUID'),
            'agent_id' => Yii::t('app','Agent ID'),
            'business_location_id' => Yii::t('app','Business Location'),
            'role' => Yii::t('app',"Role"),
            'email_notification' =>Yii::t('app', 'Email Notification'),
            'assignment_agent_email' => Yii::t('app', 'Email'),
            'assignment_created_at' => Yii::t('app','Date Assigned'),
            'assignment_updated_at' => Yii::t('app','Assignment Updated At'),
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields()
    {
        return [
          'agent',
          'restaurant',
          'businessLocation'
        ];
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasOne($modelClass::className(), ['business_location_id' => 'business_location_id'])
            ->andWhere(['business_location.is_deleted' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent($modelClass = "\common\models\Agent") {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'agent_id']);
    }

    public function getAgentName($modelClass = "\common\models\Agent") {
        return $this->agent? $this->agent->agent_name: null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    public function notificationMail($password) {
        return Yii::$app->mailer->compose([
            'html' => 'new-agent',
        ], [
            'model' => $this,
            'password' => $password
        ])
            ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
            ->setTo($this->agent->agent_email)
            ->setSubject("You've been invited to manage " . $this->restaurant->name)
            ->send();
    }

    public function inviteAgent() {
        return Yii::$app->mailer->compose([
            'html' => 'agent-invitation',
        ], [
            'model' => $this
        ])
            ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
            ->setTo($this->agent->agent_email)
            ->setSubject( $this->restaurant->name  . " has added you as a team member on their Plugn store")
            ->send();
    }
}
