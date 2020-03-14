<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WorkingHours;

/**
 * WorkingHoursSearch represents the model behind the search form of `common\models\WorkingHours`.
 */
class WorkingHoursSearch extends WorkingHours
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['working_day_id'], 'integer'],
            [['restaurant_uuid', 'operating_from', 'operating_to'], 'safe'],
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
        $query = WorkingHours::find();

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
            'working_day_id' => $this->working_day_id,
            'operating_from' => $this->operating_from,
            'operating_to' => $this->operating_to,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
