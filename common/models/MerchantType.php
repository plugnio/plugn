<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "merchant_type".
 *
 * @property string $merchant_type_uuid
 * @property string $merchant_type_en
 * @property string|null $merchant_type_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property RestaurantType[] $restaurantTypes
 */
class MerchantType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_type_en'], 'required'],//'merchant_type_uuid',
            [['created_at', 'updated_at'], 'safe'],
            [['merchant_type_uuid'], 'string', 'max' => 60],
            [['merchant_type_en', 'merchant_type_ar'], 'string', 'max' => 100],
            [['merchant_type_uuid'], 'unique'],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'merchant_type_uuid',
                ],
                'value' => function () {
                    if (!$this->merchant_type_uuid)
                        $this->merchant_type_uuid = 'mt_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->merchant_type_uuid;
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
            'merchant_type_uuid' => Yii::t('app', 'Merchant Type Uuid'),
            'merchant_type_en' => Yii::t('app', 'Merchant Type En'),
            'merchant_type_ar' => Yii::t('app', 'Merchant Type Ar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[RestaurantTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTypes()
    {
        return $this->hasMany(RestaurantType::className(), ['merchant_type_uuid' => 'merchant_type_uuid']);
    }
}
