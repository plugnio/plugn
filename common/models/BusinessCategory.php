<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_category".
 *
 * @property string $business_category_uuid
 * @property string $business_category_en
 * @property string|null $business_category_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property RestaurantType[] $restaurantTypes
 */
class BusinessCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_category_en'], 'required'],//'business_category_uuid',
            [['created_at', 'updated_at'], 'safe'],
            [['business_category_uuid'], 'string', 'max' => 60],
            [['business_category_en', 'business_category_ar'], 'string', 'max' => 100],
            [['business_category_uuid'], 'unique'],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'business_category_uuid',
                ],
                'value' => function () {
                    if (!$this->business_category_uuid)
                        $this->business_category_uuid = 'bc_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->business_category_uuid;
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
            'business_category_uuid' => Yii::t('app', 'Business Category Uuid'),
            'business_category_en' => Yii::t('app', 'Business Category En'),
            'business_category_ar' => Yii::t('app', 'Business Category Ar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function  arrFilter() {
        return ArrayHelper::map(self::find()->all(), 'business_category_uuid', 'business_category_en');
    }

    /**
     * Gets query for [[RestaurantTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTypes()
    {
        return $this->hasMany(RestaurantType::className(), ['business_category_uuid' => 'business_category_uuid']);
    }
}
