<?php

namespace agent\models;

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
        return $fields;
    }

}
