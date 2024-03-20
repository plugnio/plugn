<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_shipping_method".
 *
 * @property string $restaurant_uuid
 * @property int $shipping_method_id
 *
 * @property Restaurant $restaurant
 * @property ShippingMethod $shippingMethod
 */
class RestaurantShippingMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_shipping_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'shipping_method_id'], 'required'],
            [['shipping_method_id'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid', 'shipping_method_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'shipping_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['shipping_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShippingMethod::className(), 'targetAttribute' => ['shipping_method_id' => 'shipping_method_id']],
        ];
    }

    /**
     * @return array|false|int[]|string[]
     */
    public function extraFields()
    {
        return array_merge(parent::extraFields(), [
            'shippingMethod'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'shipping_method_id' => Yii::t('app', 'Shipping Method ID'),
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->restaurant->getShippingMethods();

        $methods = ShippingMethod::find()->all();

        $props = [];

        foreach ($methods as $method) {

            $key = strtolower($method->name_en) . "_enabled";

            $props[$key] = $this->restaurant->getRestaurantShippingMethods()
                ->andWhere(['shipping_method_id' => $method->shipping_method_id])
                ->exists();
        }

        $props["number_of_shipping_partners"] = $this->restaurant->getRestaurantShippingMethods()->count();

        Yii::$app->eventManager->track("Shipping Settings Updated", $props, null, $this->restaurant_uuid);
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
     * Gets query for [[ShippingMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShippingMethod($modelClass = "\common\models\ShippingMethod")
    {
        return $this->hasOne($modelClass::className(), ['shipping_method_id' => 'shipping_method_id']);
    }
}
