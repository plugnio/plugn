<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Item;

/**
 * ItemSearch represents the model behind the search form of `common\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_uuid', 'restaurant_uuid', 'item_name', 'item_name_ar', 'item_description', 'item_description_ar', 'item_image', 'item_created_at', 'item_updated_at'], 'safe'],
            [['sort_number', 'stock_qty','unit_sold'], 'integer'],
            [['item_price'], 'number'],
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
     * @param sting $restaurantUuid
     *
     * @return ActiveDataProvider
     */
    public function search($params, $restaurantUuid)
    {
        $query = Item::find()->where(['restaurant_uuid' => $restaurantUuid]);;

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
            'sort_number' => $this->sort_number,
            'stock_qty' => $this->stock_qty,
            'unit_sold' => $this->unit_sold,
            'item_price' => $this->item_price,
            'item_created_at' => $this->item_created_at,
            'item_updated_at' => $this->item_updated_at,
        ]);

        $query->andFilterWhere(['like', 'item_uuid', $this->item_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'item_name_ar', $this->item_name_ar])
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'item_description_ar', $this->item_description_ar])
            ->andFilterWhere(['like', 'item_image', $this->item_image]);

        return $dataProvider;
    }
}
