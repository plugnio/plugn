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
     * @param $userId
     * @return void
     */
    public function track($event, $eventData, $timestamp = null, $userId = null)
    {
        $store_id = Yii::$app->request->headers->get('Store-Id');

        if(!Yii::$app->user->isGuest && $store_id) {

            $assignment = AgentAssignment::find()
                ->andWhere(['restaurant_uuid' => $store_id, "agent_id" => Yii::$app->user->getId()])
                ->one();

            if($assignment)
                $eventData["role"] = $assignment->role;
        }

        parent::track($event, $eventData, $timestamp, $store_id);
    }
}
