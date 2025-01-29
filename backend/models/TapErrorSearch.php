<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TapError;

/**
 * TapErrorSearch represents the model behind the search form of `common\models\TapError`.
 */
class TapErrorSearch extends TapError
{
    public $store_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_name', 'statusName',  'tap_error_uuid', 'restaurant_uuid', 'title', 'message', 'text', 'created_at', 'updated_at'], 'safe'],
            [['issue_logged', 'status'], 'integer'],
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
        $query = TapError::find()
            ->joinWith(['restaurant'])
            ->orderBy('created_at DESC');

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

        $dataProvider->sort->attributes['store_name'] = [
            'asc' => ['restaurant.name' => SORT_ASC],
            'desc' => ['restaurant.name' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'issue_logged' => $this->issue_logged,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tap_error_uuid', $this->tap_error_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'restaurant.name', $this->store_name])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
