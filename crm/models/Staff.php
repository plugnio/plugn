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
        $fields['staffAssignment'] = function($model) {

            $staff = \Yii::$app->accountManager->getManagedAccount ();

            return $this->getStaffAssignments()
                ->andWhere(['restaurant_uuid'=>$staff['restaurant_uuid']])
                ->one();
        };
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null, $modelClass = "\crm\models\StaffToken") {
        return parent::findIdentityByAccessToken($token, $type, $modelClass);
    }

    /**
     * Get all Restaurant accounts this staff is assigned to manage
     * @return \yii\db\ActiveQuery
     */
    public function getAccountsManaged($modelClass = "\crm\models\Restaurant")
    {
        return parent::getAccountsManaged ($modelClass);
    }

    /**
     * All assignment records made for this staff
     * @return \yii\db\ActiveQuery
     */
    public function getStaffAssignments($modelClass = "\crm\models\StaffAssignment")
    {
        return parent::getStaffAssignments ($modelClass);
    }
}
