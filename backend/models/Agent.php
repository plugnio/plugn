<?php

namespace backend\models;


/**
 * Agent model
 */
class Agent extends \common\models\Agent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge([
            ['agent_email_verification', 'boolean']
        ], parent::rules());
    }
}