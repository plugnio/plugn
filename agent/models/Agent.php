<?php

namespace agent\models;

use yii\db\Expression;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "Agent".
 * It extends from \common\models\Agent but with custom functionality for Candidate application module
 *
 */
class Agent extends \common\models\Agent implements IdentityInterface {


    public function fields()
    {
        $fields = parent::fields();
        $fields['agentAssignment'] = function($model) {

            $agent = \Yii::$app->accountManager->getManagedAccount ();

            return $this->getAgentAssignments()
                ->andWhere(['agent_assignment.restaurant_uuid'=>$agent['restaurant_uuid']])
                ->one();
        };
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\agent\models\AgentToken") {
        return parent::findIdentityByAccessToken($token, $type, $modelClass);
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
