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
 * @property int|null $item_type
 * @property int|null $track_quantity
 * @property string $barcode
 * @property string $sku
 * @property int|null $stock_qty
 * @property int|null $prep_time
 * @property int|null $prep_time_unit
 * @property int|null $unit_sold
 * @property string|null $item_image
 * @property float|null $item_price
 * @property float|null $compare_at_price
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

    //Values for `prep_time_unit`
    const TIME_UNIT_MIN = 'min';
    const TIME_UNIT_HRS = 'hrs';
    const TIME_UNIT_DAY = 'day';

    //Values for `item_status`
    const ITEM_STATUS_PUBLISH = 1;
    const ITEM_STATUS_UNPUBLISH = 2;

    const TYPE_SIMPLE = 1;
    const TYPE_CONFIGURABLE = 2;

    const SCENARIO_UPDATE_STATUS = 'update-status';

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
        //sku barcode track_quantity stock_qty

        return [
            [['item_name', 'prep_time_unit', 'prep_time'], 'required', 'on' => 'create'],
            ['prep_time_unit', 'in', 'range' => [self::TIME_UNIT_MIN, self::TIME_UNIT_HRS, self::TIME_UNIT_DAY]],
            [['item_name', 'item_name_ar', 'items_category', 'restaurant_uuid'], 'required'],
            [['sort_number', 'stock_qty', 'item_type'], 'integer', 'min' => 0],
            [['unit_sold'], 'integer', 'min' => 0],
            [['item_price','compare_at_price'], 'number', 'min' => 0],
            [['track_quantity', 'prep_time'], 'integer'],
            ['item_status', 'in', 'range' => [self::ITEM_STATUS_PUBLISH, self::ITEM_STATUS_UNPUBLISH]],
            [['stock_qty'], 'required', 'when' => function ($model) {
                return $model->track_quantity && $model->item_type == self::TYPE_SIMPLE;
            }],
            ['item_price', 'required', 'when' => function ($model) {
                return $model->item_type == self::TYPE_SIMPLE;
            }],
            ['item_price', 'default', 'value' => 0],
            [['item_images'], 'file', 'extensions' => 'jpg, jpeg , png', 'maxFiles' => 10],
            [['item_created_at', 'item_updated_at', 'items_category'], 'safe'],
            [['item_uuid'], 'string', 'max' => 300],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['item_name', 'item_name_ar', 'item_image', 'barcode', 'sku'], 'string', 'max' => 255],
            [['item_description', 'item_description_ar'], 'string', 'max' => 2500],
            [['item_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className (), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }


    public function scenarios()
    {
        $parent = parent::scenarios();

        $parent[self::SCENARIO_UPDATE_STATUS] = ['item_status'];
        return $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_uuid' => 'Item uuid',
            'restaurant_uuid' => 'Restaurant uuid',
            'item_name' => 'Title in English',
            'item_name_ar' => 'Title in Arabic',
            'item_description' => 'Item description in English',
            'item_description_ar' => 'Item description in Arabic',
            'sort_number' => 'Sort number',
            'stock_qty' => 'Stock quantity',
            'track_quantity' => 'Track quantity',
            'unit_sold' => 'Unit sold',
            'barcode' => 'Barcode (ISBN, UPC, GTIN, etc.)',
            'sku' => 'SKU (Stock Keeping Unit)',
            'item_image' => 'Item image',
            'item_price' => 'Price',
            'compare_at_price' => 'Compare at price',
            'prep_time' => 'Preparation time',
            'prep_time_unit' => 'Preparation time unit',
            'item_status' => 'Item status',
            'items_category' => 'Category',
            'item_created_at' => 'Item created at',
            'item_updated_at' => 'Item updated qt',
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getTimeUnit()
    {
        switch ($this->prep_time_unit) {
            case self::TIME_UNIT_MIN:
                return "Minutes";
                break;
            case self::TIME_UNIT_HRS:
                return $this->prep_time == 1 ? "Hour" : "Hours";
                break;
            case self::TIME_UNIT_DAY:
                return $this->prep_time == 1 ? "Day" : "Days";
                break;
        }
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_uuid',
                ],
                'value' => function () {
                    if (!$this->item_uuid) {
                        $this->item_uuid = 'item_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->item_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'item_created_at',
                'updatedAtAttribute' => 'item_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    // public function fields()
    // {
    //     $fields = parent::fields ();
    //
    //     // remove fields that contain sensitive information
    //     unset($fields['item_created_at']);
    //     unset($fields['item_updated_at']);
    //     unset($fields['unit_sold']);
    //     unset($fields['barcode']);
    //     unset($fields['sku']);
    //
    //     return $fields;
    // }


    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        $fields = parent::fields();

        return array_merge ($fields, [
            'currency',
            'options',
            'itemImages',
            'extraOptions',
            'itemVariants'
        ]);
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if ($insert || isset($changedAttributes['item_name']))
        {
            if ($this->restaurant->sitemap_require_update == 0)
            {
                $this->restaurant->sitemap_require_update = 1;

                Restaurant::updateAll([
                    'sitemap_require_update' => $this->restaurant->sitemap_require_update
                ], [
                    'restaurant_uuid' => $this->restaurant_uuid
                ]);
                //$this->restaurant->save (false);
            }
        }

        return true;
    }

    /**
     * increase stock_qty
     * @param type $qty
     */
    public function increaseStockQty($qty)
    {
        if ($this->track_quantity)
            $this->stock_qty += $qty;

        $this->unit_sold -= $qty;

        self::updateAll([
            'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_uuid' => $this->item_uuid
        ]);
    }

    /**
     * decrease stock_qty
     * @param type $qty
     */
    public function decreaseStockQty($qty)
    {
        if ($this->track_quantity)
            $this->stock_qty -= $qty;

        $this->unit_sold += $qty;

        self::updateAll([
            'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_uuid' => $this->item_uuid
        ]);
    }

    /**
     * save items category
     */
    public function saveItemsCategory($items_categories)
    {
        CategoryItem::deleteAll (['item_uuid' => $this->item_uuid]);

        foreach ($items_categories as $category_id) {
            $item_category = new CategoryItem();
            $item_category->category_id = $category_id;
            $item_category->item_uuid = $this->item_uuid;
            $item_category->save ();
        }
    }

    /**
     * Upload item image  to Cloudinary
     * @param type $imagesPath
     */
    public function uploadItemImage($imagesPath)
    {
        //deleteallofThem
//        foreach ($this->getItemImages()->all() as  $itemImage) {
//          $itemImage->delete();
//        }

        foreach ($imagesPath as $key => $path) {


            $filename = Yii::$app->security->generateRandomString ();

            $itemName = str_replace (' ', '', $this->item_name);

            try {
                $result = Yii::$app->cloudinaryManager->upload (
                    $path['file'],
                    [
                        'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                    ]
                );

                if ($result || count ($result) > 0) {
                    $item_image_model = new ItemImage();
                    $item_image_model->item_uuid = $this->item_uuid;
                    $item_image_model->product_file_name = basename ($result['url']);
                    $item_image_model->save (false);
                }

                unlink ($path['file']);

            } catch (\Cloudinary\Error $err) {
                Yii::error ("Error when uploading item's image to Cloudinry: " . json_encode ($err));
                Yii::error ("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode ($imagesPath));

            }
        }
    }

    public function beforeDelete()
    {
        foreach ($this->getItemImages ()->all () as $itemImage) {
            $itemImage->delete ();
        }

        return parent::beforeDelete ();
    }

    public function findItemImageByFileName($file_name)
    {
        return ItemImage::find ()
            ->andWhere (['item_uuid' => $this->item_uuid, 'product_file_name' => $file_name])
            ->one ();
    }

    /**
     * Gets query for [[ItemVariant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariants($model = 'common\models\ItemVariant')
    {
        return $this->hasMany ($model::className (), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems($model = 'common\models\CategoryItem')
    {
        return $this->hasMany ($model::className (), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory($modelClass = "\common\models\Category")
    {
        return $this->hasMany ($modelClass::className (), ['category_id' => 'category_id'])->via ('categoryItems');
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories($modelClass = "\common\models\Category")
    {
        return $this->hasMany ($modelClass::className (), ['category_id' => 'category_id'])
            ->viaTable ('category_item', ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne ($modelClass::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne ($modelClass::className (), ['currency_id' => 'currency_id'])->via ('restaurant');
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions($modelClass = "\common\models\Option")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages($model = 'common\models\ItemImage')
    {
        return $this->hasMany ($model::className (), ['item_uuid' => 'item_uuid']);
    }


    public function getItemImage($model = 'common\models\ItemImage')
    {
        return $this->hasOne ($model::className (), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions($modelClass = "\common\models\ExtraOption")
    {
        return $this->hasMany ($modelClass::className (), ['option_id' => 'option_id'])->via ('options');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status', [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     */
    public function getSoldUnitsInSpecifcDate($start_date = null, $end_date = null, $modelClass = "\common\models\OrderItem")
    {
        $query = $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ]);

        if($start_date && $end_date) {
            $query->andWhere (['between', 'order.order_created_at', $start_date, $end_date]);
        }

        return $query->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getTodaySoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->andWhere (['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')])
            ->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getThisWeekSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->andWhere (['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
            ->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getCurrentMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->andWhere ('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere ('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
            ->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->andWhere ('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere ('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->sum ('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastThreeMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->joinWith ('order')
            ->andWhere ([
                'IN',
                'order.order_status',
                [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->andWhere ('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere ('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
            ->sum ('qty');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasMany ($modelClass::className (), ['order_uuid' => 'order_uuid'])->via ('orderItems');
    }

    public static function find() {
        return new query\ItemQuery(get_called_class());
    }
}
