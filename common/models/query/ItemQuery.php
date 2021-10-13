<?php


namespace common\models\query;


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
        return $this->joinWith(['categoryItems'])
            ->andWhere(['category_item.category_id' => $category_id]);
    }
}