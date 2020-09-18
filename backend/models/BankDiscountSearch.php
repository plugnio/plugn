<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BankDiscount;

/**
 * BankDiscountSearch represents the model behind the search form of `common\models\BankDiscount`.
 */
class BankDiscountSearch extends BankDiscount
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_discount_id', 'bank_id', 'discount_type', 'discount_amount'], 'integer'],
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
        $query = BankDiscount::find();

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
            'bank_discount_id' => $this->bank_discount_id,
            'bank_id' => $this->bank_id,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
