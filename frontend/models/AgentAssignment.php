<?php


namespace frontend\models;


class AgentAssignment extends \common\models\AgentAssignment
{
    public $agent_name;

    public function rules()
    {
        return array_merge (parent::rules (), [
            [['agent_name'], 'string'],
        ]);
    }
}