<?php

namespace crm\models;

/**
 * It extends from \common\models\Restaurant but with custom functionality for Candidate application module
 *
 */
class Restaurant extends \common\models\Restaurant {


    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }
}
