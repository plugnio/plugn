<?php

namespace agent\models;


class AgentToken extends \common\models\AgentToken
{
    /**
     * Gets query for [[Agent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgent($modelClass = "\agent\models\Agent")
    {
        return parent::getAgent($modelClass);
    }
}