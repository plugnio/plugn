<?php

namespace agent\models;

class Restaurant extends \common\models\Restaurant {

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        $fields['store_email'] = function($model) {
            return $model->restaurant_email;
        };
        return $fields;
    }

}
