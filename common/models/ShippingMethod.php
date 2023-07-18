<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "shipping_method".
 *
 * @property int $shipping_method_id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $code
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property RestaurantShippingMethod[] $restaurantShippingMethods
 * @property Restaurant[] $restaurantUus
 */
class ShippingMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name_en', 'name_ar'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 20],
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className (),
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
            'shipping_method_id' => Yii::t('app', 'Sm ID'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ar' => Yii::t('app', 'Name Ar'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[RestaurantShippingMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantShippingMethods($modelClass = "\common\models\RestaurantShippingMethod")
    {
        return $this->hasMany($modelClass::className(), ['shipping_method_id' => 'shipping_method_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->viaTable('restaurant_shipping_method', ['shipping_method_id' => 'shipping_method_id']);
    }
}
