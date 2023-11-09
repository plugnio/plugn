<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessItemType;

/**
 * BusinessItemTypeSearch represents the model behind the search form of `common\models\BusinessItemType`.
 */
class BusinessItemTypeSearch extends BusinessItemType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_item_type_uuid', 'business_item_type_en', 'business_item_type_ar', 'business_item_type_subtitle_en', 'business_item_type_subtitle_ar', 'created_at', 'updated_at'], 'safe'],
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
        $query = BusinessItemType::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'business_item_type_uuid', $this->business_item_type_uuid])
            ->andFilterWhere(['like', 'business_item_type_en', $this->business_item_type_en])
            ->andFilterWhere(['like', 'business_item_type_ar', $this->business_item_type_ar])
            ->andFilterWhere(['like', 'business_item_type_subtitle_en', $this->business_item_type_subtitle_en])
            ->andFilterWhere(['like', 'business_item_type_subtitle_ar', $this->business_item_type_subtitle_ar]);

        return $dataProvider;
    }
}
