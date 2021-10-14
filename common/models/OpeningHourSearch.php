<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OpeningHour;

/**
 * OpeningHourSearch represents the model behind the search form of `common\models\OpeningHour`.
 */
class OpeningHourSearch extends OpeningHour
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['opening_hour_id', 'day_of_week', 'is_closed'], 'integer'],
            [['restaurant_uuid', 'open_at', 'close_at'], 'safe'],
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
        $query = OpeningHour::find();

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
            'opening_hour_id' => $this->opening_hour_id,
            'day_of_week' => $this->day_of_week,
            'open_at' => $this->open_at,
            'close_at' => $this->close_at,
            'is_closed' => $this->is_closed,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
