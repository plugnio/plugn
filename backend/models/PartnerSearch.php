<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Partner;

/**
 * PartnerSearch represents the model behind the search form of `common\models\Partner`.
 */
class PartnerSearch extends Partner
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_uuid', 'username', 'partner_auth_key', 'partner_password_hash', 'partner_password_reset_token', 'partner_email', 'referral_code', 'partner_created_at', 'partner_updated_at'], 'safe'],
            [['partner_status'], 'integer'],
            [['commission'], 'number'],
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
        $query = Partner::find();

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
            'partner_status' => $this->partner_status,
            'commission' => $this->commission,
            'partner_created_at' => $this->partner_created_at,
            'partner_updated_at' => $this->partner_updated_at,
        ]);

        $query->andFilterWhere(['like', 'partner_uuid', $this->partner_uuid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'partner_auth_key', $this->partner_auth_key])
            ->andFilterWhere(['like', 'partner_password_hash', $this->partner_password_hash])
            ->andFilterWhere(['like', 'partner_password_reset_token', $this->partner_password_reset_token])
            ->andFilterWhere(['like', 'partner_email', $this->partner_email])
            ->andFilterWhere(['like', 'referral_code', $this->referral_code]);

        return $dataProvider;
    }
}
