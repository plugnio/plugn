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
 * @property float $delivery_fee
 * @property float $min_charge
 *
 * @property Area $area
 * @property Restaurant $restaurantUu
 */
class DeliveryZoneForm extends \yii\db\ActiveRecord {

    public $delivery_fee;
    public $min_delivery_time;
    public $city_id;
    public $min_charge;
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['min_delivery_time'], 'integer'],
            [['delivery_fee','min_charge'], 'number'],
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

        $restaurantDeliveryAreas = RestaurantDelivery::find()->with('area')->all();

        foreach ($restaurantDeliveryAreas as $restaurantDeliveryArea) {
            if ($restaurantDeliveryArea->area->city_id == $this->city_id) {
                
                if($this->delivery_fee != null)
                    $restaurantDeliveryArea->delivery_fee = $this->delivery_fee;
                
                if($this->min_delivery_time != null)
                    $restaurantDeliveryArea->min_delivery_time = $this->min_delivery_time;
                
                if($this->min_charge != null)
                    $restaurantDeliveryArea->min_charge = $this->min_charge;
                
                $restaurantDeliveryArea->save(false);
            }
        }
        

        return true;
    }

}
