<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Option;

/**
 * OptionSearch represents the model behind the search form of `common\models\Option`.
 */
class OptionSearch extends Option
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_id', 'min_qty', 'max_qty'], 'integer'],
            [['item_uuid', 'option_name', 'option_name_ar'], 'safe'],
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
        $query = Option::find();

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
            'option_id' => $this->option_id,
            'min_qty' => $this->min_qty,
            'max_qty' => $this->max_qty,
        ]);

        $query->andFilterWhere(['like', 'item_uuid', $this->item_uuid])
            ->andFilterWhere(['like', 'option_name', $this->option_name])
            ->andFilterWhere(['like', 'option_name_ar', $this->option_name_ar]);

        return $dataProvider;
    }
}
