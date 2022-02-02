<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Voucher;

/**
 * VoucherSearch represents the model behind the search form of `common\models\Voucher`.
 */
class VoucherSearch extends Voucher
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voucher_id', 'discount_type', 'discount_amount','voucher_status', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'integer'],
            [['restaurant_uuid', 'code', 'valid_from', 'valid_until',  'voucher_created_at', 'voucher_updated_at'], 'safe'],
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
      $query = Voucher::find()->where(['restaurant_uuid' => $storeUuid])
                ->with('activeOrders')
                ->orderBy([
                  'voucher_created_at' => SORT_DESC
                ]);

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
            'voucher_id' => $this->voucher_id,
            'discount_amount' => $this->discount_amount,
            'discount_type' => $this->discount_type,
            'voucher_status' => $this->voucher_status,
            'valid_from' => $this->valid_from,
            'valid_until' => $this->valid_until,
            'max_redemption' => $this->max_redemption,
            'limit_per_customer' => $this->limit_per_customer,
            'minimum_order_amount' => $this->minimum_order_amount,
            'voucher_created_at' => $this->voucher_created_at,
            'voucher_updated_at' => $this->voucher_updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
