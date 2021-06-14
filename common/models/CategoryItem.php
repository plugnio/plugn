<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category_item".
 *
 * @property int $category_item_id
 * @property int $category_id
 * @property string $item_uuid
 *
 * @property Category $category
 * @property Item $itemUu
 */
class CategoryItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'item_uuid'], 'required'],
            [['category_id'], 'integer'],
            [['item_uuid'], 'string', 'max' => 300],
            [['category_id', 'item_uuid'], 'unique', 'targetAttribute' => ['category_id', 'item_uuid']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'item_uuid' => 'Item Uuid',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory($model = '\common\models\Category')
    {
        return $this->hasOne($model::className(), ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['item_uuid' => 'item_uuid']);
    }
}
