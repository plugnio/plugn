<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_item_type".
 *
 * @property string $rit_uuid
 * @property string $restaurant_uuid
 * @property string $business_item_type_uuid
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BusinessItemType $businessItemType
 * @property Restaurant $restaurant
 */
class RestaurantItemType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_item_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'business_item_type_uuid'], 'required'],//'rit_uuid',
            [['created_at', 'updated_at'], 'safe'],
            [['rit_uuid', 'restaurant_uuid', 'business_item_type_uuid'], 'string', 'max' => 60],
            [['rit_uuid'], 'unique'],
            [['business_item_type_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessItemType::className(), 'targetAttribute' => ['business_item_type_uuid' => 'business_item_type_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'rit_uuid',
                ],
                'value' => function () {
                    if (!$this->rit_uuid)
                        $this->rit_uuid = 'rit_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->rit_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            'rit_uuid' => Yii::t('app', 'Rit Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'business_item_type_uuid' => Yii::t('app', 'Business Item Type Uuid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[BusinessItemTypeUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessItemType()
    {
        return $this->hasOne(BusinessItemType::className(), ['business_item_type_uuid' => 'business_item_type_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
