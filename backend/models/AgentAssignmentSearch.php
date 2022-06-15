<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AgentAssignment;

/**
 * AgentAssignmentSearch represents the model behind the search form of `common\models\AgentAssignment`.
 */
class AgentAssignmentSearch extends AgentAssignment
{

    public $store_name;
    public $agent_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assignment_id', 'agent_id', 'role'], 'integer'],
            [['agent_name','store_name','restaurant_uuid', 'assignment_agent_email', 'assignment_created_at', 'assignment_updated_at'], 'safe'],
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
        $query = AgentAssignment::find()->joinWith(['agent', 'restaurant']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->attributes['agent_name'] = [
            'asc' => ['agent.agent_name' => SORT_ASC],
            'desc' => ['agent.agent_name' => SORT_DESC],
        ];


        $dataProvider->sort->attributes['store_name'] = [
            'asc' => ['restaurant.name' => SORT_ASC],
            'desc' => ['restaurant.name' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'assignment_id' => $this->assignment_id,
            'agent_id' => $this->agent_id,
            'assignment_created_at' => $this->assignment_created_at,
            'assignment_updated_at' => $this->assignment_updated_at,
            'role' => $this->role,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'restaurant.name', $this->store_name])
            ->andFilterWhere(['like', 'agent.agent_name', $this->agent_name])
            ->andFilterWhere(['like', 'assignment_agent_email', $this->assignment_agent_email]);

        return $dataProvider;
    }
}
