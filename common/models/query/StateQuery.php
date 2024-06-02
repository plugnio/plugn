<?php

namespace common\models\query;

class StateQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return State[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['state.is_deleted' => 0]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return State|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['state.is_deleted' => 0]);
        return parent::one ($db);
    }
}