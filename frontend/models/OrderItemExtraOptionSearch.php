<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderItemExtraOption;

/**
 * OrderItemExtraOptionSearch represents the model behind the search form of `common\models\OrderItemExtraOption`.
 */
class OrderItemExtraOptionSearch extends OrderItemExtraOption
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_item_extra_option_id', 'order_item_id', 'extra_option_id'], 'integer'],
            [['extra_option_name', 'extra_option_name_ar'], 'safe'],
            [['extra_option_price'], 'number'],
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
        $query = OrderItemExtraOption::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_item_extra_option_id' => $this->order_item_extra_option_id,
            'order_item_id' => $this->order_item_id,
            'extra_option_id' => $this->extra_option_id,
            'extra_option_price' => $this->extra_option_price,
        ]);

        $query->andFilterWhere(['like', 'extra_option_name', $this->extra_option_name])
            ->andFilterWhere(['like', 'extra_option_name_ar', $this->extra_option_name_ar]);

        return $dataProvider;
    }
}
