<?php


namespace agent\models;

use Yii;

/**
 * This is the model class for table "Order".
 * It extends from \common\models\Order but with custom functionality for Order module
 *
 */
class OpeningHour extends \common\models\OpeningHour
{

    public function rules()
    {
        return array_merge (parent::rules (), [
            [['open_at', 'close_at'], 'date', 'format' => 'H:m:s'],
        ]);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }
}
