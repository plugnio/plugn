<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\City;

/**
 * CitySearch represents the model behind the search form of `frontend\models\City`.
 */
class CitySearch extends City
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_name', 'city_name_ar', 'country_id'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = City::find();

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
            'country_id' => $this->country_id
        ]);

        $query->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'city_name_ar', $this->city_name_ar]);

        return $dataProvider;
    }
}