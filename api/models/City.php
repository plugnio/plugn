<?php

namespace api\models;
  
use Yii;


/**
 * This is the model class for table "City".
 * It extends from \common\models\City but with custom functionality for Api application module
 *
 */
class City extends \common\models\City {

	public function extraFields() {
		return array_merge(parent::extraFields(), [
			'areaDeliveryZone'
		]);
	}

	/**
	 * Gets query for [[AreaDeliveryZone]].
	 *
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
            ->one();
	}
}