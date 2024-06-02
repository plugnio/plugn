<?php

namespace common\models\query;

class AreaQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['area.is_deleted' => 0]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['area.is_deleted' => 0]);
        return parent::one ($db);
    }
}