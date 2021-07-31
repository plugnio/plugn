<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bank;

/**
 * BankSearch represents the model behind the search form of `common\models\Bank`.
 */
class BankSearch extends Bank
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_id', 'deleted'], 'integer'],
            [['bank_name', 'bank_iban_code', 'bank_swift_code', 'bank_address', 'bank_transfer_type', 'bank_created_at', 'bank_updated_at'], 'safe'],
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
    public function search($params)
    {
        $query = Bank::find();

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
            'bank_id' => $this->bank_id,
            'bank_created_at' => $this->bank_created_at,
            'bank_updated_at' => $this->bank_updated_at,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_iban_code', $this->bank_iban_code])
            ->andFilterWhere(['like', 'bank_swift_code', $this->bank_swift_code])
            ->andFilterWhere(['like', 'bank_address', $this->bank_address])
            ->andFilterWhere(['like', 'bank_transfer_type', $this->bank_transfer_type]);

        return $dataProvider;
    }
}
