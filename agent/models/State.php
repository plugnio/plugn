<?php

namespace agent\models;

use Yii;
use yii\db\Expression;

class State extends \common\models\State
{
    public function extraFields()
    {
        return array_merge(parent::extraFields(), [
           "deliveryToWholeState" => function($model) {

                $restaurantUuid = Yii::$app->request->headers->get('Store-Id');
                $delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");

                $query = AreaDeliveryZone::find()
                    ->andWhere([
                        'restaurant_uuid' => $restaurantUuid,
                        "state_id" => $model->state_id
                    ])
                    ->andWhere(new Expression("area_id IS NULL AND city_id IS NULL"));

                if($delivery_zone_id) {
                    $query->andWhere(['delivery_zone_id' => $delivery_zone_id]);
                }

                return $query->exists();
           }
        ]);
    }
}