<?php

namespace crm\models;


class StaffToken extends \common\models\StaffToken
{
    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff($modelClass = "\crm\models\Staff")
    {
        return parent::getStaff($modelClass);
    }
}