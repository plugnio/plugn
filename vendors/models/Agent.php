<?php

namespace vendors\models;

use Yii;

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

        // remove fields that contain sensitive information
        unset($fields['agent_auth_key'], $fields['agent_password_hash'], $fields['agent_password_reset_token']);


        return $fields;
    }


}
