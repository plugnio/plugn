<?php

namespace crm\models;

use yii\db\Expression;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "Staff".
 * It extends from \common\models\Staff but with custom functionality for Candidate application module
 *
 */
class Staff extends \common\models\Staff implements IdentityInterface {


    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\crm\models\StaffToken") {
        return parent::findIdentityByAccessToken($token, $type, $modelClass);
    }
}
