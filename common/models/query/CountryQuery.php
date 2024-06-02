<?php

namespace common\models\query;

class CountryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Country[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['country.is_deleted' => 0]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Country|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['country.is_deleted' => 0]);
        return parent::one ($db);
    }
}
