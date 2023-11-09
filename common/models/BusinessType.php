<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "business_type".
 *
 * @property string $business_type_uuid
 * @property string|null $parent_business_type_uuid
 * @property string $business_type_en
 * @property string|null $business_type_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BusinessType $parentBusinessTypeUu
 * @property BusinessType[] $businessTypes
 * @property RestaurantType[] $restaurantTypes
 */
class BusinessType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_type_en'], 'required'],//'business_type_uuid',
            [['created_at', 'updated_at'], 'safe'],
            [['business_type_uuid', 'parent_business_type_uuid'], 'string', 'max' => 60],
            [['business_type_en', 'business_type_ar'], 'string', 'max' => 100],
            [['business_type_uuid'], 'unique'],
            [['parent_business_type_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessType::className(), 'targetAttribute' => ['parent_business_type_uuid' => 'business_type_uuid']],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'business_type_uuid',
                ],
                'value' => function () {
                    if (!$this->business_type_uuid)
                        $this->business_type_uuid = 'bit_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->business_type_uuid;
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
            'business_type_uuid' => Yii::t('app', 'Business Type Uuid'),
            'parent_business_type_uuid' => Yii::t('app', 'Parent Business Type Uuid'),
            'business_type_en' => Yii::t('app', 'Business Type En'),
            'business_type_ar' => Yii::t('app', 'Business Type Ar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ParentBusinessTypeUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentBusinessType()
    {
        return $this->hasOne(BusinessType::className(), ['business_type_uuid' => 'parent_business_type_uuid']);
    }

    /**
     * Gets query for [[BusinessTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessTypes()
    {
        return $this->hasMany(BusinessType::className(), ['parent_business_type_uuid' => 'business_type_uuid']);
    }

    /**
     * Gets query for [[RestaurantTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTypes()
    {
        return $this->hasMany(RestaurantType::className(), ['business_type_uuid' => 'business_type_uuid']);
    }
}
