<?php

namespace common\models\query;

use common\models\Item;
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
     * @return ItemQuery
     */
    public function filterPublished()
    {
        return $this->andWhere(['item.item_status' => Item::ITEM_STATUS_PUBLISH]);
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

    /**
   * filter by given keyword
   * @param $keyword
   * @return ItemQuery
   */
  public function filterKeyword($keyword) {
      return $this->andWhere([
          'OR',
          ['like', 'item.item_name',   preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword)],
          ['like', 'item.item_name_ar', preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword)],
          ['like', 'item.item_description', preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword)],
          ['like', 'item.item_description_ar', preg_replace('/[^\p{L}\p{N}\s]/u', '', $keyword)]
      ]);
  }
}
