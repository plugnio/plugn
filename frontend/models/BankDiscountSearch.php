<?php

namespace frontend\models;

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
            [['bank_discount_id', 'bank_id', 'discount_type', 'discount_amount', 'bank_discount_status', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'integer'],
            [['restaurant_uuid', 'valid_from', 'valid_until', 'bank_discount_created_at', 'bank_discount_updated_at'], 'safe'],
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
        $query = BankDiscount::find()->where(['restaurant_uuid' => $storeUuid]);;

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
            'bank_discount_id' => $this->bank_discount_id,
            'bank_id' => $this->bank_id,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'bank_discount_status' => $this->bank_discount_status,
            'valid_from' => $this->valid_from,
            'valid_until' => $this->valid_until,
            'max_redemption' => $this->max_redemption,
            'limit_per_customer' => $this->limit_per_customer,
            'minimum_order_amount' => $this->minimum_order_amount,
            'bank_discount_created_at' => $this->bank_discount_created_at,
            'bank_discount_updated_at' => $this->bank_discount_updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
