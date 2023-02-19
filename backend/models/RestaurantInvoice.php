<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantInvoice as RestaurantInvoiceModel;

/**
 * RestaurantInvoice represents the model behind the search form of `common\models\RestaurantInvoice`.
 */
class RestaurantInvoice extends RestaurantInvoiceModel
{
    public $restaurantName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_uuid', 'invoice_number', 'restaurant_uuid', 'payment_uuid', 'currency_code', 'created_at', 'updated_at', 'restaurantName'], 'safe'],
            [['amount'], 'number'],
            [['invoice_status'], 'integer'],
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
        $query = RestaurantInvoiceModel::find()
            ->joinWith('restaurant');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'invoice_number' => [
                        'asc' => ['invoice_number' => SORT_ASC],
                        'desc' => ['invoice_number' => SORT_DESC]
                    ],
                    'amount' => [
                        'asc' => ['amount' => SORT_ASC],
                        'desc' => ['amount' => SORT_DESC]
                    ],
                    'created_at' => [
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC]
                    ],
                    'invoice_status' => [
                        'asc' => ['invoice_status' => SORT_ASC],
                        'desc' => ['invoice_status' => SORT_DESC]
                    ],
                    'restaurantName' => [
                        'asc' => ['restaurant.name' => SORT_ASC],
                        'desc' => ['restaurant.name' => SORT_DESC]
                    ]
                ],
                'defaultOrder' => ['created_at' => 'DESC']
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'amount' => $this->amount,
            'invoice_status' => $this->invoice_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'invoice_uuid', $this->invoice_uuid])
            ->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid])
            ->andFilterWhere(['like', 'currency_code', $this->currency_code]);

        if($this->restaurantName) {
            $query->andFilterWhere(['like', 'restaurant.name', $this->restaurantName]);
        }

        return $dataProvider;
    }
}
