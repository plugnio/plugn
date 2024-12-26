<?php

namespace common\models\query;

use yii\db\Expression;

class PartnerQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(new Expression('partner_deleted_at IS NULL'));
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(new Expression('partner_deleted_at IS NULL'));
        return parent::one($db);
    }
}