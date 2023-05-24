<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_by_campaign".
 *
 * @property string $rbc_uuid
 * @property string $restaurant_uuid
 * @property string $utm_uuid
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurant
 * @property Campaign $utm
 */
class RestaurantByCampaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_by_campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'utm_uuid'], 'required'],
            [['created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['rbc_uuid', 'restaurant_uuid', 'utm_uuid'], 'string', 'max' => 60],
            [['rbc_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['utm_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['utm_uuid' => 'utm_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'rbc_uuid',
                ],
                'value' => function() {
                    if (!$this->rbc_uuid)
                        $this->rbc_uuid = 'rbc_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->rbc_uuid;
                }
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => null
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
            'rbc_uuid' => Yii::t('app', 'Rbc Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'utm_uuid' => Yii::t('app', 'Utm Uuid'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Utm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtm($modelClass = "\common\models\Campaign")
    {
        return $this->hasOne($modelClass::className(), ['utm_uuid' => 'utm_uuid']);
    }
}
