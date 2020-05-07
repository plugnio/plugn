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
 * @property float|null $item_price
 * @property string|null $item_created_at
 * @property string|null $item_updated_at
 *
 * @property CategoryItem[] $categoryItems
 * @property Category[] $categories
 * @property Restaurant $restaurant
 * @property Option[] $options
 * @property ExtraOptions[] $extraOptions
 */
class Item extends \yii\db\ActiveRecord {

    public $items_category;
    public $image;

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
            [['item_name', 'items_category'], 'required', 'on' => 'create'],
            [['item_name','item_name_ar', 'item_price'], 'required'],
            [['sort_number', 'stock_qty'], 'integer' , 'min'=> 0],
            [['item_price'], 'number', 'min'=> 0],
            [['image'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 1],
            [['item_created_at', 'item_updated_at', 'items_category'], 'safe'],
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
            'item_description_ar' => 'Item Description in Arabic',
            'sort_number' => 'Sort Number',
            'stock_qty' => 'Stock Qty',
            'item_image' => 'Item Image',
            'image' => 'Item Image',
            'item_price' => 'Price',
            'items_category' => 'Category',
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

    /**
     * increase stock_qty
     * @param type $qty
     */
    public function increaseStockQty($qty) {
        $this->stock_qty += $qty;
        $this->save(false);
    }

    /**
     * decrease stock_qty
     * @param type $qty
     */
    public function decreaseStockQty($qty) {
        $this->stock_qty -= $qty;
       $this->save(false);
    }


    /**
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && isset($changedAttributes['item_image']) && $this->image) {
            if ($changedAttributes['item_image']) {
                $this->deleteItemImage($changedAttributes['item_image']);
            }
        }

    }

    /**
     * save items category
     */
    public function saveItemsCategory($items_categories) {

        CategoryItem::deleteAll(['item_uuid' => $this->item_uuid]);

        foreach ($items_categories as $category_id) {
            $item_category = new CategoryItem();
            $item_category->category_id = $category_id;
            $item_category->item_uuid = $this->item_uuid;
            $item_category->save();
        }
    }

    /**
     * Upload item image  to cloudinary
     * @param type $imageURL
     */
    public function uploadItemImage($imageURL) {

        $filename = Yii::$app->security->generateRandomString();
//        $restaurantName = str_replace(' ', '', $this->restaurant->name);
        $itemName = str_replace(' ', '', $this->item_name);

        try {
            $result = Yii::$app->cloudinaryManager->upload(
                    $imageURL, [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                    ]
            );

            if ($result || count($result) > 0) {
                $this->item_image = basename($result['url']);
                $this->save();
            }
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading venue photos to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     * Return item image url to dispaly it on backend
     * @return string
     */
    public function getItemImage() {
        $photo_url = [];

        if ($this->item_image) {
            $restaurantName = str_replace(' ', '', $this->restaurant->name);
            $url = 'https://res.cloudinary.com/plugn/image/upload/restaurants/'
                    . $restaurantName . '/items/'
                    . $this->item_image;
            $photo_url = $url;
        }

        return $photo_url;
    }

    /**
     * Delete Item's image
     */
    public function deleteItemImage($item_image = null) {

        if (!$item_image)
            $item_image = $this->item_image;

        $restaurantName = str_replace(' ', '', $this->restaurant->name);
        $imageURL = "restaurants/" . $restaurantName . "/items/" . $item_image;

        try {
            Yii::$app->cloudinaryManager->delete($imageURL);
        } catch (\Cloudinary\Error $err) {
            Yii::error('Error when uploading item image to Cloudinry: ' . json_encode($err));
        }
    }

    /**
     *
     * @return boolean
     */
    public function beforeDelete() {


        if (!parent::beforeDelete()) {
            return false;
        }

        $this->deleteItemImage();

        return true;
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
    public function getRestaurant() {
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
    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions() {
        return $this->hasMany(ExtraOption::className(), ['option_id' => 'option_id'])->via('options');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid']);
    }

}
