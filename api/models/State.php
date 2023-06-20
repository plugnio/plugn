<?php

namespace api\models;
  
use Yii;
use yii\db\Expression;


/**
 * This is the model class for table "State".
 * It extends from \common\models\State but with custom functionality for Api application module
 *
 */
class State extends \common\models\State {

	public function extraFields() {
		return array_merge(parent::extraFields(), [
			'areaDeliveryZone'
		]);
	}

    public function getCities($modelClass = "\api\models\City") {
        return parent::getCities($modelClass);
    }

	/**
	 * Gets query for [[AreaDeliveryZone]].
	 * area delivery zone where "select all" for states selected from dashboard app 
	 * @return \yii\db\ActiveQuery
	 */
	public function getAreaDeliveryZone($modelClass = "\common\models\AreaDeliveryZone")
	{
		$store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        if(!$store_id)
            return null;

	  	return parent::getAreaDeliveryZones($modelClass)
	  		->andWhere([
	  			'restaurant_uuid' => $store_id,
	  		])
	  		->andWhere(new Expression("city_id IS NULL"))
            ->one();
	}
}