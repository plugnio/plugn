<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PlanPrice;

/**
 * PlanPriceSearch represents the model behind the search form of `common\models\PlanPrice`.
 */
class PlanPriceSearch extends PlanPrice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_price_id', 'plan_id'], 'integer'],
            [['currency', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = PlanPrice::find();

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
            'plan_price_id' => $this->plan_price_id,
            'plan_id' => $this->plan_id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}
