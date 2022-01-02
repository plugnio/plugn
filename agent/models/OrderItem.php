<?php


namespace agent\models;


class OrderItem extends \common\models\OrderItem
{
    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\agent\models\Item") {
        return parent::getItem ($modelClass);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImage($modelClass = "\agent\models\ItemImage")
    {
        return parent::getItemImage($modelClass);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\agent\models\Order") {
        return parent::getOrder($modelClass);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }

    /**
     * Gets query for [[OrderItemExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\agent\models\OrderItemExtraOption") {
        return parent::getOrderItemExtraOptions ($modelClass);
    }

    public function getOrderExtraOptionsText()
    {
        $value = [];
        if (count($this->orderItemExtraOptions) > 0) {
            foreach ($this->orderItemExtraOptions as $extra) {
                $value[] = $extra['extra_option_name'];
            }
            if (count($value) > 0) {
                return implode(',', $value);
            }

            return '(NOT SET)';
        }
    }
}
