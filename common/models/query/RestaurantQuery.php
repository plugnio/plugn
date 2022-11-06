<?php

namespace common\models\query;

class RestaurantQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        //$this->andWhere(['!=', 'restaurant.is_deleted', 1]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        //$this->andWhere(['!=', 'restaurant.is_deleted', 1]);
        return parent::one ($db);
    }
}