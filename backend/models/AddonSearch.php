<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Addon;

/**
 * AddonSearch represents the model behind the search form of `common\models\Addon`.
 */
class AddonSearch extends Addon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addon_uuid', 'name', 'name_ar', 'description', 'description_ar', 'slug', 'created_at', 'updated_at'], 'safe'],
            [['price', 'special_price'], 'number'],
            [['expected_delivery', 'sort_number', 'status', 'created_by', 'updated_by'], 'integer'],
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
        $query = Addon::find();

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
            'price' => $this->price,
            'special_price' => $this->special_price,
            'expected_delivery' => $this->expected_delivery,
            'sort_number' => $this->sort_number,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'addon_uuid', $this->addon_uuid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_ar', $this->name_ar])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'description_ar', $this->description_ar])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
