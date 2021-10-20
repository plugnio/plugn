<?php

namespace agent\models;

use Yii;

class Category extends \common\models\Category {


  /**
   * @return array|false
   */
  public function extraFields()
  {
      $fields = parent::extraFields();

      $fields[] = 'items';
      $fields[] = 'itemQuantity';

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
     * Gets query for [[ItemUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems($modelClass = "\agent\models\Item")
    {
        return parent::getItems ($modelClass)->limit(5);
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
