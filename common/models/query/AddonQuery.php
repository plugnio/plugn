<?php

namespace common\models\query;


class AddonQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * filter by keyword
     * @param $query
     * @return AddonQuery
     */
    public function filterKeyword($query)
    {
        return $this->andWhere([
            'AND',
            ['like', 'name', $query],
            ['like', 'name_ar', $query]
        ]);
    }
}
