<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "item".
 *
 * @property string $item_uuid
 * @property string $restaurant_uuid
 * @property string|null $item_name
 * @property string|null $item_name_ar
 * @property string|null $item_description
 * @property string|null $item_description_ar
 * @property int|null $sort_number
 * @property int|null $stock_qty
 * @property string|null $item_image
 * @property float|null $price
 * @property string|null $item_created_at
 * @property string|null $item_updated_at
 *
 * @property CategoryItem[] $categoryItems
 * @property Category[] $categories
 * @property Restaurant $restaurantUu
 * @property Option[] $options
 */
class Item extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['item_name'], 'required'],
            [['sort_number', 'stock_qty'], 'integer'],
            [['price'], 'number'],
            [['item_created_at', 'item_updated_at'], 'safe'],
            [['item_uuid'], 'string', 'max' => 300],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['item_name', 'item_name_ar', 'item_image'], 'string', 'max' => 255],
            [['item_description', 'item_description_ar'], 'string', 'max' => 1000],
            [['item_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'item_uuid' => 'Item Uuid',
            'restaurant_uuid' => 'Restaurant Uuid',
            'item_name' => 'Item Name',
            'item_name_ar' => 'Item Name Ar',
            'item_description' => 'Item Description',
            'item_description_ar' => 'Item Description Ar',
            'sort_number' => 'Sort Number',
            'stock_qty' => 'Stock Qty',
            'item_image' => 'Item Image',
            'price' => 'Price',
            'item_created_at' => 'Item Created At',
            'item_updated_at' => 'Item Updated At',
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_uuid',
                ],
                'value' => function() {
                    if (!$this->item_uuid)
                        $this->item_uuid = 'item_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->item_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'item_created_at',
                'updatedAtAttribute' => 'item_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert) {

        if($insert)
            $this->restaurant_uuid = Yii::$app->user->identity->restaurant_uuid;

        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems() {
        return $this->hasMany(CategoryItem::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['category_id' => 'category_id'])->viaTable('category_item', ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUu() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions() {
        return $this->hasMany(Option::className(), ['item_uuid' => 'item_uuid']);
    }

}
