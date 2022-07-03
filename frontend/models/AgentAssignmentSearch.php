<?php

namespace frontend\models;

use agent\models\AgentAssignment;
use yii\data\ActiveDataProvider;


/**
 * AgentAssignmentSearch represents the model behind the search form of `common\models\AgentAssignment`.
 */
class AgentAssignmentSearch extends AgentAssignment
{
    public $agent_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'agent_name', 'business_location_id', 'assignment_agent_email', 'assignment_created_at', 'assignment_updated_at', 'role', 'email_notification', 'reminder_email', 'receive_weekly_stats'], 'safe'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios ();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $restaurant_uuid)
    {
        $query = AgentAssignment::find ()
            ->joinWith('agent')
            ->andWhere(['restaurant_uuid' => $restaurant_uuid]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load ($params);


        if (!$this->validate ()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        /*$query->andFilterWhere ([
            'agent_id' => $this->agent_id,
            'agent_status' => $this->agent_status,
            'agent_created_at' => $this->agent_created_at,
            'agent_updated_at' => $this->agent_updated_at,
        ]);*/

        $query->andFilterWhere (['like', 'agent.agent_name', $this->agent_name])
            ->andFilterWhere (['like', 'assignment_agent_email', $this->assignment_agent_email])
            ->andFilterWhere (['like', 'role', $this->role]);

        return $dataProvider;
    }
}
