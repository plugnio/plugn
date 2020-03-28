<?php

namespace frontend\models;

use Yii;
use frontend\models\City;
use common\models\Restaurant;
use common\models\RestaurantDelivery;

/**
 *
 * @property string $restaurant_uuid
 * @property int $area_id
 * @property int|null $delivery_time
 * @property float $delivery_fee
 * @property float $min_charge
 *
 * @property Area $area
 * @property Restaurant $restaurant
 */
class DeliveryZoneForm extends \yii\db\ActiveRecord {

    public $delivery_fee;
    public $delivery_time;
    public $city_id;
    public $restaurant_uuid;
    public $min_charge;
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['delivery_time'], 'integer','min' => 0],
            [['delivery_fee','min_charge'], 'number' ,'min' => 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'city_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'delivery_time' => 'Delivery Time',
            'delivery_fee' => 'Delivery Fee',
            'min_charge' => 'Min Charge',
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

        $restaurantDeliveryAreas = RestaurantDelivery::find()->with('area')->where(['restaurant_uuid' => $this->restaurant_uuid])->all();

        foreach ($restaurantDeliveryAreas as $restaurantDeliveryArea) {
            if ($restaurantDeliveryArea->area->city_id == $this->city_id) {
                
                if($this->delivery_fee != null)
                    $restaurantDeliveryArea->delivery_fee = $this->delivery_fee;
                
                if($this->delivery_time != null)
                    $restaurantDeliveryArea->delivery_time = $this->delivery_time;
                
                if($this->min_charge != null)
                    $restaurantDeliveryArea->min_charge = $this->min_charge;
                
                $restaurantDeliveryArea->save(false);
            }
        }
        

        return true;
    }

}
