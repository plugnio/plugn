<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DeliveryZone;

/**
 * DeliveryZoneSearch represents the model behind the search form of `common\models\DeliveryZone`.
 */
class DeliveryZoneSearch extends DeliveryZone
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_zone_id', 'business_location_id', 'delivery_time'], 'integer'],
            [['delivery_fee', 'min_charge'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $restaurantUuid, $businessLocationId)
    {
        $query = \Yii::$app->accountManager->getManagedAccount($restaurantUuid)
            ->getDeliveryZones()
            ->with(['country', 'businessLocation.country','currency'])
            ->andWhere(['delivery_zone.business_location_id' => $businessLocationId]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'delivery_zone_id' => $this->delivery_zone_id,
            'business_location_id' => $this->business_location_id,
            'delivery_time' => $this->delivery_time,
            'delivery_fee' => $this->delivery_fee,
            'min_charge' => $this->min_charge,
        ]);

        return $dataProvider;
    }
}
