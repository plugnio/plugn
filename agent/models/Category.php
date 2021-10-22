<?php

namespace agent\models;

use Yii;

class Category extends \common\models\Category {


    public $itemPerPageLimit = 5;
  /**
   * @return array|false
   */
  public function extraFields()
  {
      $fields = parent::extraFields();

      $fields[] = 'allItems';
      $fields['pagination'] = function($model) {
          return [
              'totalPage' => ceil($this->itemQuantity/$this->itemPerPageLimit),
              'totalCount' => $this->itemQuantity,
              'currentPage' => 1,
              'pagePage' => $this->itemPerPageLimit,
          ];
      };
      return $fields;
  }

  public function fields()
  {
      $field = parent::fields();
      $field['isChecked'] = function ($model) {
          return false;
      };
      return $field;
  }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems($modelClass = "\agent\models\CategoryItem")
    {
        return parent::getCategoryItems ($modelClass);
    }

    /**
     * @param string $modelClass
     * @return array|\common\models\query\Agent[]|\yii\db\ActiveRecord[]
     */
    public function getAllItems()
    {
        $model = $this->getCategoryItems()
                ->joinWith('item')
                ->orderBy ([new \yii\db\Expression('item.sort_number ASC')])
                ->limit($this->itemPerPageLimit);
        return $model->all();
    }

    /**
     * @param string $modelClass
     * @return bool|int|string|null
     */
    public function getItemQuantity($modelClass = "\agent\models\Item")
    {
        return parent::getItems ($modelClass)->count();
    }
}
