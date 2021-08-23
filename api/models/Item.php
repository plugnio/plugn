<?php

namespace api\models;

use common\models\ItemImage;
use common\models\Option;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Item".
 * It extends from \common\models\Item but with custom functionality for Api application module
 *
 */
class Item extends \common\models\Item {
  /**
   * @inheritdoc
   */
  public function fields()
  {
      $fields = parent::fields ();

      // remove fields that contain sensitive information
      unset($fields['item_created_at']);
      unset($fields['item_updated_at']);
      unset($fields['unit_sold']);
      unset($fields['barcode']);
      unset($fields['sku']);

      return $fields;
  }

}
