<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $category_id
 * @property int $restaurant_uuid
 * @property string|null $category_name
 * @property string|null $category_name_ar
 * @property int|null $sort_number
 *
 * @property CategoryItem[] $categoryItems
 * @property Item[] $itemUus
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['sort_number'], 'integer', 'min'=> 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['category_name', 'category_name_ar'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
            'category_name_ar' => 'Category Name Ar',
            'sort_number' => 'Sort Number',
            'restaurant_uuid' => 'Restaurant UUID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
    
    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems() {
        return $this->hasMany(CategoryItem::className(), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[ItemUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['item_uuid' => 'item_uuid'])
                        ->viaTable('category_item', ['category_id' => 'category_id'])
                        ->orderBy(['sort_number' => SORT_ASC]);
    }

}
