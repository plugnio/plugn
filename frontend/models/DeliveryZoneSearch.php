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
            [['delivery_zone_id', 'business_location_id', 'support_delivery', 'support_pick_up', 'delivery_time'], 'integer'],
            [['business_location_name', 'business_location_name_ar'], 'safe'],
            [['delivery_fee', 'min_charge'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DeliveryZone::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'support_delivery' => $this->support_delivery,
            'support_pick_up' => $this->support_pick_up,
            'delivery_time' => $this->delivery_time,
            'delivery_fee' => $this->delivery_fee,
            'min_charge' => $this->min_charge,
        ]);

        $query->andFilterWhere(['like', 'business_location_name', $this->business_location_name])
            ->andFilterWhere(['like', 'business_location_name_ar', $this->business_location_name_ar]);

        return $dataProvider;
    }
}
