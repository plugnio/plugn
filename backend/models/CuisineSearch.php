<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cuisine;

/**
 * CuisineSearch represents the model behind the search form of `common\models\Cuisine`.
 */
class CuisineSearch extends Cuisine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cuisine_id'], 'integer'],
            [['cuisine_name', 'cuisine_name_ar'], 'safe'],
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
        $query = Cuisine::find();

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
            'cuisine_id' => $this->cuisine_id,
        ]);

        $query->andFilterWhere(['like', 'cuisine_name', $this->cuisine_name])
            ->andFilterWhere(['like', 'cuisine_name_ar', $this->cuisine_name_ar]);

        return $dataProvider;
    }
}
