<?php
namespace api\components;

use Yii;


class EventManager extends \common\components\EventManager
{
    /**
     * @param $event
     * @param $eventData
     * @param $timestamp
     * @param $userId
     * @return void
     */
    public function track($event, $eventData, $timestamp = null, $store_id = null)
    {
        if(!$store_id) {
            $store_id = Yii::$app->request->headers->get('Store-Id');
        }

        $eventData["channel"] = "Store Web App";

        parent::track($event, $eventData, $timestamp, $store_id);
    }
}
