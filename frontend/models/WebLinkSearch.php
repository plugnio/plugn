<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WebLink;

/**
 * WebLinkSearch represents the model behind the search form of `common\models\WebLink`.
 */
class WebLinkSearch extends WebLink
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['web_link_id', 'web_link_type'], 'integer'],
            [['restaurant_uuid', 'url', 'web_link_title'], 'safe'],
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
    public function search($params, $restaurantUuid)
    {
        $query = WebLink::find()->where(['restaurant_uuid' => $restaurantUuid]);

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
            'web_link_id' => $this->web_link_id,
            'web_link_type' => $this->web_link_type,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'web_link_title', $this->web_link_title]);

        return $dataProvider;
    }
}
