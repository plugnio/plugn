<?php

namespace agent\models;

use Yii;

/**
 * This is the model class for table "Item".
 * It extends from \common\models\Item but with custom functionality for Candidate application module
 *
 */
class Item extends \common\models\Item {

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        $fields['unit_sold'] = function($model) {
         return $model->unit_sold;
        };

        $fields['sku'] = function($model) {
         return $model->sku;
        };

        $fields['barcode'] = function($model) {
         return $model->barcode;
        };

        return $fields;
    }

}
