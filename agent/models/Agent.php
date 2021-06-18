<?php

namespace agent\models;

use agent\models\AgentAssignment;
use agent\models\Restaurant;


/**
 * This is the model class for table "Agent".
 * It extends from \common\models\Agent but with custom functionality for Candidate application module
 *
 */
class Agent extends \common\models\Agent {

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();
        return $fields;
    }

    /**
     * Get all Restaurant accounts this agent is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged($modelClass = "\agent\models\Restaurant")
    {
        return parent::getAccountsManaged ($modelClass);
    }

    /**
     * All assignment records made for this agent
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments($modelClass = "\agent\models\AgentAssignment")
    {
        return parent::getAgentAssignments ($modelClass);
    }
}
