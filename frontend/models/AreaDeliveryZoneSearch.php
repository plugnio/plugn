<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AreaDeliveryZone;

/**
 * AreaDeliveryZoneSearch represents the model behind the search form of `common\models\AreaDeliveryZone`.
 */
class AreaDeliveryZoneSearch extends AreaDeliveryZone
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_delivery_zone', 'delivery_zone_id', 'country_id', 'city_id', 'area_id'], 'integer'],
            [['restaurant_uuid'], 'safe'],
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
        $query = AreaDeliveryZone::find();

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
            'area_delivery_zone' => $this->area_delivery_zone,
            'delivery_zone_id' => $this->delivery_zone_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
