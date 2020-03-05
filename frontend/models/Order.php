<?php

namespace frontend\models;
use common\models\Area;
use common\models\RestaurantDelivery;
use Yii;

/**
 * This is the model class for table "order".
 * 
 * @property string|null $restaurant_uuid 
 * @property RestaurantDelivery $restaurantDelivery
 */
class Order extends \common\models\Order {

     /**
     * Calculate order item's total price
     */
    public function calculateOrderItemsTotalPrice() {
        $totalPrice = 0;

        foreach ($this->getOrderItems()->all() as $item)
            $totalPrice += $item->calculateOrderItemPrice();

        return $totalPrice;
    }
    
        /**
     * Calculate order's total price
     */
    public function calculateOrderTotalPrice() {
        $totalPrice = 0;

        foreach ($this->getOrderItems()->all() as $item)
            $totalPrice += $item->calculateOrderItemPrice();

        $totalPrice += $this->restaurantDelivery->delivery_fee;
        
        return $totalPrice;
    }
    
    /**
     * Gets query for [[RestaurantDelivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery() {
        return $this->hasOne(RestaurantDelivery::className(), ['area_id' => 'area_id'])->via('area')->andWhere(['restaurant_uuid' => \Yii::$app->user->identity->restaurant_uuid]);
    }
    
}
