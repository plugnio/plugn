<?php


namespace agent\models;


class ExtraOption extends \common\models\ExtraOption
{
    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption($modelClass = "\agent\models\Option") {
        return parent::getOption ($modelClass);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\agent\models\Item") {
        return parent::getItem ($modelClass);
    }

    /**
     * Gets query for [[OrderItemExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\agent\models\OrderItemExtraOption") {
        return parent::getOrderItemExtraOptions ($modelClass);
    }
}