<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusinessLocation;

/**
 * BusinessLocationSearch represents the model behind the search form of `common\models\BusinessLocation`.
 */
class BusinessLocationSearch extends BusinessLocation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_location_id', 'support_delivery', 'support_pick_up'], 'integer'],
            [['restaurant_uuid', 'business_location_name', 'business_location_name_ar'], 'safe'],
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
        $query = BusinessLocation::find()->where(['restaurant_uuid' => $restaurantUuid]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'business_location_id' => $this->business_location_id,
            'support_delivery' => $this->support_delivery,
            'support_pick_up' => $this->support_pick_up,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'business_location_name', $this->business_location_name])
            ->andFilterWhere(['like', 'business_location_name_ar', $this->business_location_name_ar]);

        return $dataProvider;
    }
}
