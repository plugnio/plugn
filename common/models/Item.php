<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ItemImage;
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
 * @property int|null $track_quantity
 * @property string $barcode
 * @property string $sku
 * @property int|null $stock_qty
 * @property int|null $unit_sold
 * @property string|null $item_image
 * @property float|null $item_price
 * @property string|null $item_created_at
 * @property string|null $item_updated_at
 *
 * @property CategoryItem[] $categoryItems
 * @property Category[] $categories
 * @property Restaurant $restaurant
 * @property Option[] $options
 * @property OrderItem[] $orderItems
 * @property ExtraOptions[] $extraOptions
 * @property ItemImage[] $itemImages
 */
class Item extends \yii\db\ActiveRecord
{
    public $items_category;
    public $item_images;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'items_category'], 'required', 'on' => 'create'],
            [['item_name', 'item_name_ar', 'item_price'], 'required'],
            [['sort_number', 'stock_qty'], 'integer', 'min' => 0],
            [['unit_sold'], 'integer', 'min' => 0],
            [['item_price'], 'number', 'min' => 0],
            [['track_quantity'], 'integer'],
            ['stock_qty', 'required', 'when' => function($model) {
                return $model->track_quantity;
            }],
            [['item_images'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 10],
            [['item_created_at', 'item_updated_at', 'items_category'], 'safe'],
            [['item_uuid'], 'string', 'max' => 300],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['item_name', 'item_name_ar', 'item_image','barcode', 'sku'], 'string', 'max' => 255],
            [['item_description', 'item_description_ar'], 'string', 'max' => 2000],
            [['item_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_uuid' => 'Item Uuid',
            'restaurant_uuid' => 'Restaurant Uuid',
            'item_name' => 'Title in English',
            'item_name_ar' => 'Title in Arabic',
            'item_description' => 'Item Description in English',
            'item_description_ar' => 'Item Description in Arabic',
            'sort_number' => 'Sort Number',
            'stock_qty' => 'Stock Quantity',
            'track_quantity' => 'Track Quantity',
            'unit_sold' => 'Unit Sold',
            'barcode' => 'Barcode (ISBN, UPC, GTIN, etc.)',
            'sku' => 'SKU (Stock Keeping Unit)',
            'item_image' => 'Item Image',
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
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_uuid',
                ],
                'value' => function () {
                    if (!$this->item_uuid) {
                        $this->item_uuid = 'item_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

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
    public function increaseStockQty($qty)
    {
      if($this->track_quantity)
        $this->stock_qty += $qty;

      $this->unit_sold -= $qty;
      $this->save(false);
    }

    /**
     * decrease stock_qty
     * @param type $qty
     */
    public function decreaseStockQty($qty)
    {
        if($this->track_quantity)
          $this->stock_qty -= $qty;

        $this->unit_sold += $qty;
        $this->save(false);
    }

    /**
     * save items category
     */
    public function saveItemsCategory($items_categories)
    {
        CategoryItem::deleteAll(['item_uuid' => $this->item_uuid]);

        foreach ($items_categories as $category_id) {
            $item_category = new CategoryItem();
            $item_category->category_id = $category_id;
            $item_category->item_uuid = $this->item_uuid;
            $item_category->save();
        }
    }

    /**
     * Upload item image  to Cloudinary
     * @param type $imagesPath
     */
    public function uploadItemImage($imagesPath)
    {
        //deleteallofThem
        foreach ($this->getItemImages()->all() as  $itemImage) {
          $itemImage->delete();
        }

        foreach ($imagesPath as $key => $path) {
            $filename = Yii::$app->security->generateRandomString();

            $itemName = str_replace(' ', '', $this->item_name);

            try {
                $result = Yii::$app->cloudinaryManager->upload(
                    $path->tempName,
                    [
                'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                    ]
                );

                if ($result || count($result) > 0) {
                    $item_image_model = new ItemImage();
                    $item_image_model->item_uuid = $this->item_uuid;
                    $item_image_model->product_file_name = basename($result['url']);
                    $item_image_model->save(false);
                }
            } catch (\Cloudinary\Error $err) {
                Yii::error("Error when uploading item's image to Cloudinry: " . json_encode($err));
            }
        }
    }


    public function beforeDelete()
    {
        foreach ($this->getItemImages()->all() as  $itemImage) {
            $itemImage->delete();
        }


        return parent::beforeDelete();
    }


    public function findItemImageByFileName($file_name)
    {
        return ItemImage::find()->where(['item_uuid' => $this->item_uuid, 'product_file_name' => $file_name])->one();
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems()
    {
        return $this->hasMany(CategoryItem::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(Category::className(), ['category_id' => 'category_id'])->via('categoryItems');
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['category_id' => 'category_id'])->viaTable('category_item', ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Option::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages()
    {
        return $this->hasMany(ItemImage::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions()
    {
        return $this->hasMany(ExtraOption::className(), ['option_id' => 'option_id'])->via('options');
    }


    /**
     * Gets query for [[Options]].
     *
     */
    public function getSoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getTodaySoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getThisWeekSoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getCurrentMonthSoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastMonthSoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastThreeMonthSoldUnits(){
      return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->where(['order.order_status' => Order::STATUS_PENDING])
            ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
            ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
            ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
            ->orWhere(['order_status' => Order::STATUS_CANCELED])
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
            ->sum('qty');
    }



    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['item_uuid' => 'item_uuid']);
    }
    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasMany(Order::className(), ['order_uuid' => 'order_uuid'])->via('orderItems');
    }
}
