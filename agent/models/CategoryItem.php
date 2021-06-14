<?php

namespace agent\models;

use Yii;

class CategoryItem extends \common\models\CategoryItem
{

    public function extraFields()
    {
        return [
            'category',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory($model = '\agent\models\Category')
    {
        return parent::getCategory($model);
    }
}
