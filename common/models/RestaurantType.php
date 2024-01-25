<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_type".
 *
 * @property string $restaurant_type_uuid
 * @property string $restaurant_uuid
 * @property string|null $merchant_type_uuid
 * @property string|null $business_type_uuid
 * @property string|null $business_category_uuid
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BusinessCategory $businessCategory
 * @property BusinessType $businessType
 * @property MerchantType $merchantType
 * @property Restaurant $restaurant
 */
class RestaurantType extends \yii\db\ActiveRecord
{
    public $arrRestaurantItemTypes;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'required'],//'restaurant_type_uuid',
            [['created_at', 'updated_at'], 'safe'],
            [['restaurant_type_uuid', 'restaurant_uuid', 'merchant_type_uuid', 'business_type_uuid', 'business_category_uuid'], 'string', 'max' => 60],
            [['restaurant_type_uuid'], 'unique'],
            [['business_category_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessCategory::className(), 'targetAttribute' => ['business_category_uuid' => 'business_category_uuid']],
            [['business_type_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessType::className(), 'targetAttribute' => ['business_type_uuid' => 'business_type_uuid']],
            [['merchant_type_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => MerchantType::className(), 'targetAttribute' => ['merchant_type_uuid' => 'merchant_type_uuid']],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'restaurant_type_uuid',
                ],
                'value' => function () {
                    if (!$this->restaurant_type_uuid)
                        $this->restaurant_type_uuid = 'rt_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->restaurant_type_uuid;
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
            'restaurant_type_uuid' => Yii::t('app', 'Restaurant Type Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'merchant_type_uuid' => Yii::t('app', 'Merchant Type Uuid'),
            'business_type_uuid' => Yii::t('app', 'Business Type Uuid'),
            'business_category_uuid' => Yii::t('app', 'Business Category Uuid'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        /*if($insert) {

            Yii::$app->eventManager->track('Store Type', [
                'merchant_type' => $this->merchantType ? $this->merchantType->merchant_type_en : null,
                'business_type' => $this->businessType ? $this->businessType->business_type_en : null,
                'business_category' => $this->businessCategory ? $this->businessCategory->business_category_en : null,
            ],
                null,
                $this->restaurant_uuid
            );

            //Yii::$app->user->getId()
        }*/
    }

    /**
     * Gets query for [[BusinessCategoryUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessCategory()
    {
        return $this->hasOne(BusinessCategory::className(), ['business_category_uuid' => 'business_category_uuid']);
    }

    /**
     * Gets query for [[BusinessTypeUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessType()
    {
        return $this->hasOne(BusinessType::className(), ['business_type_uuid' => 'business_type_uuid']);
    }

    /**
     * Gets query for [[MerchantTypeUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerchantType()
    {
        return $this->hasOne(MerchantType::className(), ['merchant_type_uuid' => 'merchant_type_uuid']);
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
