<?php


namespace agent\models;


class Option extends \common\models\Option
{
    /**
     * Gets query for [[ExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions($modelClass = "\agent\models\Restaurant") {
        return parent::getExtraOptions ($modelClass);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\agent\models\Item") {
        return parent::getItem ($modelClass);
    }
}