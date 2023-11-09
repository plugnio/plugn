<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantItemType;

/**
 * RestaurantItemTypeSearch represents the model behind the search form of `common\models\RestaurantItemType`.
 */
class RestaurantItemTypeSearch extends RestaurantItemType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rit_uuid', 'restaurant_uuid', 'business_item_type_uuid', 'created_at', 'updated_at'], 'safe'],
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
        $query = RestaurantItemType::find();

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

        $query->andFilterWhere(['like', 'rit_uuid', $this->rit_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'business_item_type_uuid', $this->business_item_type_uuid]);

        return $dataProvider;
    }
}
