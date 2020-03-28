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
 * @property string $assignment_agent_email
 * @property string $assignment_created_at
 * @property string $assignment_updated_at
 *
 * @property Agent $agent
 * @property InstagramUser $user
 */
class AgentAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assignment_agent_email'], 'required'],
            [['assignment_agent_email'], 'string', 'max' => 255],
            [['assignment_agent_email'], 'email'],

            //Only allow one record of each email per account
            ['assignment_agent_email', 'unique', 'filter' => function($query){
                $query->andWhere(['restaurant_uuid' => $this->restaurant_uuid]);
            }, 'message' => 'This person has already been added as an agent.'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'assignment_created_at',
                'updatedAtAttribute' => 'assignment_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assignment_id' => 'Assignment ID',
            'restaurant_uuid' => 'Restaurant UUID',
            'agent_id' => 'Agent ID',
            'assignment_agent_email' => 'Email',
            'assignment_created_at' => 'Date Assigned',
            'assignment_updated_at' => 'Assignment Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
