<?php
namespace agent\components;

use agent\models\AgentAssignment;
use Yii;


class EventManager extends \common\components\EventManager
{
    /**
     * @param $event
     * @param $eventData
     * @param $timestamp
     * @return void
     */
    public function track($event, $eventData, $timestamp = null, $store_id = null, $onlyWallet = false)
    {
        if(!$store_id)
            $store_id = Yii::$app->request->headers->get('Store-Id');

        if(!Yii::$app->user->isGuest && $store_id) {

            $assignment = AgentAssignment::find()
                ->andWhere(['restaurant_uuid' => $store_id, "agent_id" => Yii::$app->user->getId()])
                ->one();

            if($assignment)
                $eventData["role"] = $assignment->role;
        }

        $eventData["channel"] = "Dashboard Web App";

        parent::track($event, $eventData, $timestamp, $store_id);
    }
}
