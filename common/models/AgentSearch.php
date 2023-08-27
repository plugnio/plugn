<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agent;

/**
 * AgentSearch represents the model behind the search form of `common\models\Agent`.
 */
class AgentSearch extends Agent {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['agent_id', 'agent_status'], 'integer'],
            [['utm_uuid'], 'string'],
            [['agent_name', 'agent_email', 'last_active_at', 'agent_auth_key', 'agent_password_hash', 'agent_password_reset_token', 'agent_created_at', 'agent_updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Agent::find()->orderBy(['agent_created_at' => SORT_DESC]);;

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
            'agent_id' => $this->agent_id,
            'agent_status' => $this->agent_status,
            'agent_created_at' => $this->agent_created_at,
            'agent_updated_at' => $this->agent_updated_at,
            'utm_uuid' => $this->utm_uuid
            //'last_active_at' => $this->last_active_at,
        ]);

        if($this->last_active_at) {
            $query->andWhere("DATE(last_active_at) = DATE('".
                date('Y-m-d', strtotime($this->last_active_at))."')");
        }

        $query->andFilterWhere(['like', 'agent_name', $this->agent_name])
                ->andFilterWhere(['like', 'agent_email', $this->agent_email])
                ->andFilterWhere(['like', 'agent_auth_key', $this->agent_auth_key])
                ->andFilterWhere(['like', 'agent_password_hash', $this->agent_password_hash])
                ->andFilterWhere(['like', 'agent_password_reset_token', $this->agent_password_reset_token]);

        return $dataProvider;
    }

}
