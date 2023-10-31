<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "api_log".
 *
 * @property string $log_uuid
 * @property string $restaurant_uuid
 * @property string|null $method
 * @property string|null $endpoint
 * @property string|null $request_headers
 * @property string|null $request_body
 * @property string|null $response_headers
 * @property string|null $response_body
 * @property string|null $created_at
 *
 * @property Restaurant $restaurantUu
 */
class ApiLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['restaurant_uuid'], 'required'],
            [['request_headers', 'request_body', 'response_headers', 'response_body'], 'string'],
            [['created_at'], 'safe'],
            [['log_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['method'], 'string', 'max' => 10],
            [['endpoint'], 'string'],
            [['log_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'log_uuid',
                ],
                'value' => function () {
                    if (!$this->log_uuid)
                        $this->log_uuid = 'log_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->log_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'log_uuid' => Yii::t('app', 'Log Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'method' => Yii::t('app', 'Method'),
            'endpoint' => Yii::t('app', 'Endpoint'),
            'request_headers' => Yii::t('app', 'Request Headers'),
            'request_body' => Yii::t('app', 'Request Body'),
            'response_headers' => Yii::t('app', 'Response Headers'),
            'response_body' => Yii::t('app', 'Response Body'),
            'created_at' => Yii::t('app', 'Created At'),
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
