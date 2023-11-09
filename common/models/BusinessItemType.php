<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "business_item_type".
 *
 * @property string $business_item_type_uuid
 * @property string $business_item_type_en
 * @property string|null $business_item_type_ar
 * @property string|null $business_item_type_subtitle_en
 * @property string|null $business_item_type_subtitle_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property RestaurantItemType[] $restaurantItemTypes
 */
class BusinessItemType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_item_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_item_type_en'], 'required'],//'business_item_type_uuid',
            [['business_item_type_subtitle_en', 'business_item_type_subtitle_ar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['business_item_type_uuid'], 'string', 'max' => 60],
            [['business_item_type_en', 'business_item_type_ar'], 'string', 'max' => 100],
            [['business_item_type_uuid'], 'unique'],
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'business_item_type_uuid',
                ],
                'value' => function () {
                    if (!$this->business_item_type_uuid)
                        $this->business_item_type_uuid = 'bit_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->business_item_type_uuid;
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
            'business_item_type_uuid' => Yii::t('app', 'Business Item Type Uuid'),
            'business_item_type_en' => Yii::t('app', 'Business Item Type En'),
            'business_item_type_ar' => Yii::t('app', 'Business Item Type Ar'),
            'business_item_type_subtitle_en' => Yii::t('app', 'Business Item Type Subtitle En'),
            'business_item_type_subtitle_ar' => Yii::t('app', 'Business Item Type Subtitle Ar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[RestaurantItemTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantItemTypes()
    {
        return $this->hasMany(RestaurantItemType::className(), ['business_item_type_uuid' => 'business_item_type_uuid']);
    }
}
