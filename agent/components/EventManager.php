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
        $language = Yii::$app->request->headers->get('Language');

        $eventData = array_merge($eventData, [
            "company_id" => "BAWES",
            "language" => $language,
            "channel" => "Backend",
            "do_not_disturb" => null,
        ]);

        if(!Yii::$app->user->isGuest) {

            $eventData["store_id"] = Yii::$app->request->headers->get('Store-Id');

            $assignment = AgentAssignment::find()
                ->andWhere(['restaurant_uuid' => $eventData["store_id"], "agent_id" => Yii::$app->user->getId()])
                ->one();

            if($assignment)
                $eventData["role"] = $assignment->role;
        }

        parent::track($event, $eventData, $timestamp, $userId);
    }
}
