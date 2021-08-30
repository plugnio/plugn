<?php

namespace agent\models;

use Yii;

class Category extends \common\models\Category {


  public function extraFields()
  {
      $fields = parent::extraFields();

      $fields['items'] = function ($model) {
          return $model->getItems()->with('itemImages')->all();
      };

      return $fields;
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
        return parent::getItems ($modelClass);
    }
}
