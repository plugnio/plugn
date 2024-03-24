<?php

namespace common\models\query;

use common\models\Staff;

class StaffQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        //$this->andWhere(['staff.staff_status' => Staff::STATUS_ACTIVE]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        //$this->andWhere(['staff.staff_status' => Staff::STATUS_ACTIVE]);
        return parent::one ($db);
    }

    /**
     * @return void
     */
    public function filterActive() {
        $this->andWhere(['staff.staff_status' => Staff::STATUS_ACTIVE]);
    }
}