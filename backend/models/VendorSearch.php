<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vendor;

/**
 * VendorSearch represents the model behind the search form of `common\models\Vendor`.
 */
class VendorSearch extends Vendor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vendor_id', 'vendor_status'], 'integer'],
            [['restaurant_uuid', 'vendor_name', 'vendor_email', 'vendor_auth_key', 'vendor_password_hash', 'vendor_password_reset_token', 'vendor_created_at', 'vendor_updated_at'], 'safe'],
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
        $query = Vendor::find();

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
            'vendor_id' => $this->vendor_id,
            'vendor_status' => $this->vendor_status,
            'vendor_created_at' => $this->vendor_created_at,
            'vendor_updated_at' => $this->vendor_updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'vendor_email', $this->vendor_email])
            ->andFilterWhere(['like', 'vendor_auth_key', $this->vendor_auth_key])
            ->andFilterWhere(['like', 'vendor_password_hash', $this->vendor_password_hash])
            ->andFilterWhere(['like', 'vendor_password_reset_token', $this->vendor_password_reset_token]);

        return $dataProvider;
    }
}
