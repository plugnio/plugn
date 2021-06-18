<?php


namespace agent\models;


class AgentAssignment extends \common\models\AgentAssignment
{
    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\agent\models\BusinessLocation") {
        return parent::getBusinessLocation ($modelClass);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent($modelClass = "\agent\models\Agent") {
        return parent::getAgent ($modelClass);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant ($modelClass);
    }
}