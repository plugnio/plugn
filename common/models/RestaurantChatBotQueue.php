<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_chat_bot_queue".
 *
 * @property string $rcbq_uuid
 * @property string|null $restaurant_uuid
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Restaurant $restaurant
 */
class RestaurantChatBotQueue extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_chat_bot_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['rcbq_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['rcbq_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'rcbq_uuid',
                ],
                'value' => function() {
                    if (!$this->rcbq_uuid)
                        $this->rcbq_uuid = 'rcbq_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->rcbq_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rcbq_uuid' => Yii::t('app', 'Rcbq Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
