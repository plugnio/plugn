<?php

namespace common\models\query;

use yii\db\Expression;


class ItemQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        return parent::one ($db);
    }

    /**
     * filter by item category
     * @param $category_id
     * @return ItemQuery
     */
    public function filterByCategory($category_id)
    {
        $this->joinWith(['categoryItems']);

        if($category_id == -1) {
            return $this->andWhere(new Expression('category_item.category_id IS NULL'));
        } 

        return $this->andWhere(['category_item.category_id' => $category_id]);
    }
}