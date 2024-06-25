<?php

namespace api\models;

use common\models\CategoryImage;
use common\models\Option;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Category".
 * It extends from \common\models\Category but with custom functionality for Api application module
 *
 */
class Category extends \common\models\Category {

    /**
     * @return array|\Closure[]
     */
    public function extraFields()
    {
        $fields = parent::extraFields();

        $fields['noOfItems'] = function($data) {
            return $data->getItems()
                ->count();
        };

        return $fields;
    }

    /**
   * Gets query for [[ItemUus]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getItems($modelClass = "\api\models\Item")
  {
      return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
         ->andWhere (['item_status' => Item::ITEM_STATUS_PUBLISH])
         ->viaTable ('category_item', ['category_id' => 'category_id'])
         ->orderBy ([new \yii\db\Expression('item.sort_number IS NULL, item.sort_number ASC, item.sku IS NULL, item.sku ASC')]);
  }
}
