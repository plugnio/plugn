<?php


namespace agent\models;


class Currency extends \common\models\Currency
{
    public function extraFields()
    {
        return array_merge (
            ['isStoreCurrency'],
            parent::extraFields ()
        );
    }

    public function getIsStoreCurrency()
    {
        $store = \Yii::$app->accountManager->getManagedAccount ();

        return $store->getCurrencies()
            ->andWhere(['code' => $this->code])
            ->exists();
    }

    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurants ($modelClass);
    }
}