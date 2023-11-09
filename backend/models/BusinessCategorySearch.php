<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessCategory;

/**
 * BusinessCategorySearch represents the model behind the search form of `common\models\BusinessCategory`.
 */
class BusinessCategorySearch extends BusinessCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_category_uuid', 'business_category_en', 'business_category_ar', 'created_at', 'updated_at'], 'safe'],
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
        $query = BusinessCategory::find();

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

        $query->andFilterWhere(['like', 'business_category_uuid', $this->business_category_uuid])
            ->andFilterWhere(['like', 'business_category_en', $this->business_category_en])
            ->andFilterWhere(['like', 'business_category_ar', $this->business_category_ar]);

        return $dataProvider;
    }
}
