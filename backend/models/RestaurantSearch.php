<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Restaurant;

/**
 * RestaurantSearch represents the model behind the search form of `common\models\Restaurant`.
 */
class RestaurantSearch extends Restaurant
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo', 'min_delivery_time', 'min_pickup_time', 'operating_from', 'operating_to', 'location', 'location_ar', 'phone_number', 'restaurant_created_at', 'restaurant_updated_at'], 'safe'],
            [['vendor_id', 'status', 'support_delivery', 'support_pick_up'], 'integer'],
            [['delivery_fee', 'min_charge', 'location_latitude', 'location_longitude'], 'number'],
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
        $query = Restaurant::find();

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
            'vendor_id' => $this->vendor_id,
            'status' => $this->status,
            'support_delivery' => $this->support_delivery,
            'support_pick_up' => $this->support_pick_up,
            'min_delivery_time' => $this->min_delivery_time,
            'min_pickup_time' => $this->min_pickup_time,
            'operating_from' => $this->operating_from,
            'operating_to' => $this->operating_to,
            'delivery_fee' => $this->delivery_fee,
            'min_charge' => $this->min_charge,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
            'restaurant_created_at' => $this->restaurant_created_at,
            'restaurant_updated_at' => $this->restaurant_updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_ar', $this->name_ar])
            ->andFilterWhere(['like', 'tagline', $this->tagline])
            ->andFilterWhere(['like', 'tagline_ar', $this->tagline_ar])
            ->andFilterWhere(['like', 'thumbnail_image', $this->thumbnail_image])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'location_ar', $this->location_ar])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        return $dataProvider;
    }
}
