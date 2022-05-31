<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    public $date_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['customer_name', 'customer_phone_number', 'customer_email', 'customer_created_at','date_range'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $storeUuid)
    {
        $query = Customer::find()->where(['restaurant_uuid' => $storeUuid])
          ->orderBy(['customer_created_at' => SORT_DESC]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
             ],
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


          // do we have values? if so, add a filter to our query
          if (!empty($this->date_range) && strpos($this->date_range, '-') !== false) {

              list($start_date, $end_date) = explode(' - ', $this->date_range);
              $query->andFilterWhere(['between', 'customer_created_at', $start_date, $end_date]);
          }



        // grid filtering conditions
        $query->andFilterWhere([
            'customer_id' => $this->customer_id,
            'customer_created_at' => $this->customer_created_at,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email]);

        return $dataProvider;
    }
}
