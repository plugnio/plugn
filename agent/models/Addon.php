<?php

namespace agent\models;

use Yii;


class Addon extends \common\models\Addon
{
    public function extraFields()
    {
        $fields = parent::extraFields ();

        $fields['isPurchased'] =  function ($model) {
            $restaurantUuid = Yii::$app->request->headers->get('Store-Id');

            return $model->getRestaurantAddons()
                ->andWhere(['restaurant_uuid' => $restaurantUuid])
                ->exists();
        };

        return $fields;
    }
}