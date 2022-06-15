<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PartnerPayout;

/**
 * PartnerPayoutSearch represents the model behind the search form of `common\models\PartnerPayout`.
 */
class PartnerPayoutSearch extends PartnerPayout
{

    public $partner_username;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_payout_uuid', 'partner_uuid', 'created_at', 'updated_at','partner_username'], 'safe'],
            [['amount'], 'number'],
            [['payout_status'], 'integer'],
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
        $query = PartnerPayout::find()->joinWith(['partner'])->orderBy(['created_at' => SORT_DESC]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['partner_username'] = [
            'asc' => ['partner.username' => SORT_ASC],
            'desc' => ['partner.username' => SORT_DESC],
        ];


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
            'payout_status' => $this->payout_status,
        ]);

        $query
            ->andFilterWhere(['like', 'partner_payout_uuid', $this->partner_payout_uuid])
            ->andFilterWhere(['like', 'partner.username', $this->partner_username])
            ->andFilterWhere(['like', 'partner_uuid', $this->partner_uuid]);

        return $dataProvider;
    }
}
