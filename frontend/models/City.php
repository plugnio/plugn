<?php

namespace frontend\models;

use common\models\RestaurantDelivery;
use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $city_id
 * @property string $city_name
 * @property string $city_name_ar
 *
 * @property Area[] $areas
 */
class City extends \common\models\City
{

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas()
    {
        return $this->hasMany(RestaurantDelivery::className(), ['area_id' => 'area_id'])
            ->via('areas');
    }

}
