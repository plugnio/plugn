<?php

namespace partners\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PartnerPayout;

/**
 * PartnerPayoutSearch represents the model behind the search form of `common\models\PartnerPayout`.
 */
class PartnerPayoutSearch extends PartnerPayout
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_payout_uuid', 'partner_uuid', 'payment_uuid', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = PartnerPayout::find();

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
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'partner_payout_uuid', $this->partner_payout_uuid])
            ->andFilterWhere(['like', 'partner_uuid', $this->partner_uuid])
            ->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid]);

        return $dataProvider;
    }
}
