<?php

namespace common\models\query;

class CityQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return City[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['city.is_deleted' => 0]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return City|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['city.is_deleted' => 0]);
        return parent::one ($db);
    }
}