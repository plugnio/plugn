<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Area;

/**
 * AreaSearch represents the model behind the search form of `common\models\Area`.
 */
class AreaSearch extends Area
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_id', 'city_id'], 'integer'],
            [['area_name', 'area_name_ar'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        $query = Area::find();

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
            'area_id' => $this->area_id,
            'city_id' => $this->city_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $query->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar]);

        return $dataProvider;
    }
}
