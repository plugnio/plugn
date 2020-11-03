<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Queue;

/**
 * QueueSearch represents the model behind the search form of `common\models\Queue`.
 */
class QueueSearch extends Queue
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queue_id', 'queue_status'], 'integer'],
            [['restaurant_uuid', 'queue_created_at', 'queue_updated_at', 'queue_start_at', 'queue_end_at'], 'safe'],
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
        $query = Queue::find();

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
            'queue_id' => $this->queue_id,
            'queue_status' => $this->queue_status,
            'queue_created_at' => $this->queue_created_at,
            'queue_updated_at' => $this->queue_updated_at,
            'queue_start_at' => $this->queue_start_at,
            'queue_end_at' => $this->queue_end_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
