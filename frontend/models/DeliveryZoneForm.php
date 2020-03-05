<?php

namespace frontend\models;

use frontend\models\City;
use common\models\RestaurantDelivery;
use Yii;

/**
 *
 * @property string $restaurant_uuid
 * @property int $area_id
 * @property int|null $min_delivery_time
 * @property float|null $delivery_fee
 *
 * @property Area $area
 * @property Restaurant $restaurantUu
 */
class DeliveryZoneForm extends \yii\db\ActiveRecord {

    public $delivery_fee;
    public $min_delivery_time;
    public $city_id;
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['min_delivery_time'], 'integer'],
            [['delivery_fee'], 'number'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'city_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'min_delivery_time' => 'Min Delivery Time',
            'delivery_fee' => 'Delivery Fee',
        ];
    }

    /**
     * Apply the input user entered for all areas belongs to this city
     *
     * @return Investor|null the saved model or null if saving fails
     */
    public function applyTimingAndDeliveryFeeForAllAreasBelongsToThisCity() {
        if (!$this->validate()) {
            return false;
        }

        $restaurantDeliveryAreas = RestaurantDelivery::find()->with('area')->all();

        foreach ($restaurantDeliveryAreas as $restaurantDeliveryArea) {
            if ($restaurantDeliveryArea->area->city_id == $this->city_id) {
                
                if($this->delivery_fee != null)
                    $restaurantDeliveryArea->delivery_fee = $this->delivery_fee;
                
                if($this->min_delivery_time != null)
                    $restaurantDeliveryArea->min_delivery_time = $this->min_delivery_time;
                
                $restaurantDeliveryArea->save(false);
            }
        }
        

        return true;
    }

}
