<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;
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
 * @property string|null $item_meta_title
 * @property string|null $item_meta_title_ar
 * @property string|null $item_meta_description
 * @property string|null $item_meta_description_ar
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
 * @property number|null $length
 * @property number|null $width
 * @property number|null $height
 * @property number|null $weight
 * @property boolean $shipping
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
    const SCENARIO_UPDATE_STOCK = 'update-stock';
    const SCENARIO_UPDATE_SORT = 'update-sort-number';

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
            [['item_price', 'compare_at_price'], 'number', 'min' => 0],
            [['track_quantity', 'prep_time'], 'integer'],
            //['item_status', 'in', 'range' => [self::ITEM_STATUS_PUBLISH, self::ITEM_STATUS_UNPUBLISH]],
            [['item_type'], 'default', 'value' => self::TYPE_SIMPLE],
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
            [['shipping'], 'boolean'],
            [['weight', 'length', 'height', 'width'], 'number'],//, "max" => 10 ^ 8
            [['item_name', 'item_name_ar', 'item_image', 'barcode', 'sku'], 'string', 'max' => 255],
            [['item_description', 'item_description_ar', 'item_meta_title', 'item_meta_title_ar', 'item_meta_description', 'item_meta_description_ar'], 'string', 'max' => 2500],
            [['item_uuid'], 'unique'],
            ['slug', 'safe'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    public function scenarios()
    {
        $parent = parent::scenarios();

        $parent[self::SCENARIO_UPDATE_STATUS] = ['item_status'];

        $parent[self::SCENARIO_UPDATE_STOCK] = [
            'stock_qty',
            'track_quantity'
        ];

        $parent[self::SCENARIO_UPDATE_SORT] = [
            'sort_number'
        ];

        return $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_uuid' => Yii::t('app','Item uuid'),
            'restaurant_uuid' => Yii::t('app','Restaurant uuid'),
            'item_name' => Yii::t('app','Title in English'),
            'item_name_ar' => Yii::t('app','Title in Arabic'),
            'item_description' => Yii::t('app','Item description in English'),
            'item_description_ar' => Yii::t('app','Item description in Arabic'),
            'item_meta_description' => Yii::t('app','Meta tag description'),
            'item_meta_description_ar' => Yii::t('app','Meta tag description in Arabic'),
            'item_meta_title' => Yii::t('app','Page Title'),
            'item_meta_title_ar' => Yii::t('app','Page Title in Arabic'),
            'sort_number' => Yii::t('app','Sort number'),
            'stock_qty' => Yii::t('app','Stock quantity'),
            'track_quantity' => Yii::t('app','Track quantity'),
            'unit_sold' => Yii::t('app','Unit sold'),
            'barcode' => Yii::t('app','Barcode (ISBN, UPC, GTIN, etc.)'),
            'sku' => Yii::t('app','SKU (Stock Keeping Unit)'),
            'item_image' => Yii::t('app','Item image'),
            'item_price' => Yii::t('app','Price'),
            'compare_at_price' => Yii::t('app','Compare at price'),
            'prep_time' => Yii::t('app','Preparation time'),
            'prep_time_unit' => Yii::t('app','Preparation time unit'),
            'item_status' => Yii::t('app','Item status'),
            'items_category' => Yii::t('app','Category'),
            'slug' => Yii::t('app',"Slug"),
            'item_created_at' => Yii::t('app','Item created at'),
            'item_updated_at' => Yii::t('app','Item updated at')
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
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'item_name',
                'ensureUnique' => true,
                'uniqueValidator' => ['targetAttribute' => ['restaurant_uuid', 'slug']]
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
  public function fields()
  {
      $fields = parent::fields ();

      $fields['track_quantity'] = function($data) {
          return (boolean) $data->track_quantity;
      };

      return $fields;
  }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        $fields = parent::fields();

        return array_merge($fields, [
            'currency',
            'options',
            'itemImages',
            'itemImage',
            'itemVideos',
            'extraOptions',
            'itemVariants',
            'itemSchema',
            "tabbyPromo"
        ]);
    }

    public function getTabbyPromo() {

        $fields = [];

        //todo: cache config for single api call

        $tabby_promo = Setting::getConfig($this->restaurant_uuid,
            PaymentMethod::CODE_TABBY,
            'payment_tabby_promo');

        if ($tabby_promo) {

            $tabby_promo_theme = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_promo_theme');

            $fields['tabby_promo_enabled'] = true;

            /*$max_price = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_promo_limit');

            $min_price = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_promo_min_price');

            $data['price_amount'] = $data['price']
                ? $this->tax->calculate($data['special'] ? $product_info['special'] : $product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))
                : false;

            if ($data['price_amount'])
                $data['price_amount'] = $this->currency->format($data['price_amount'], $this->session->data['currency'], '', false);

            if ($max_price && $max_price < $data['price_amount']) {
                $data['tabby_promo_enabled'] = false;
            }

            if ($min_price && $min_price >= $data['price_amount']) {
                $data['tabby_promo_enabled'] = false;
            }*/

            $tabby_promo_theme = explode(':', $tabby_promo_theme);

            $fields['promo_theme'] = array_shift($tabby_promo_theme);

            $fields['tabby_installmentsCount'] = !empty($tabby_promo_theme) ? 0 : 4;


            $payment_tabby_cc_installments_status = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_cc_installments_status');

            $payment_tabby_installments_status = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_installments_status');

            $payment_tabby_paylater_status = Setting::getConfig($this->restaurant_uuid,
                PaymentMethod::CODE_TABBY,
                'payment_tabby_paylater_status');


            $fields['productType'] = (
                    ($payment_tabby_cc_installments_status == 1) &&
                    !(
                        $payment_tabby_installments_status == 1 ||
                        $payment_tabby_paylater_status == 1
                    )
                ) ? 'creditCardInstallments' : 'installments';

        } else {
            $fields['tabby_promo_enabled'] = false;
        }

        return $fields;
    }

    public function getItemSchema()
    {
        $images = [];

        foreach ($this->itemImages as $key => $itemImage) {
            $images[] = "https://res.cloudinary.com/plugn/image/upload/q_auto:eco,w_1000/restaurants/" . $this->restaurant_uuid
                . "/items/" . $itemImage->product_file_name;
        }

        $url = $this->restaurant->restaurant_domain. '/' . $this->slug;

        $data = [
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => $this->item_name,
            "image" => $images,
            "description" => $this->item_description,
            "sku" => $this->sku,
            /*"mpn" => "925872",
            "brand": {
                      "@type": "Brand",
              "name": "ACME"
            },
            "review": {
                      "@type": "Review",
              "reviewRating": {
                          "@type": "Rating",
                "ratingValue": "4",
                "bestRating": "5"
              },
              "author": {
                          "@type": "Person",
                "name": "Fred Benson"
              }
            },
            "aggregateRating": {
                      "@type": "AggregateRating",
              "ratingValue": "4.4",
              "reviewCount": "89"
            },*/
            "offers" => [
                "@type" => "Offer",
                "url" => $url,
                "priceCurrency" => $this->restaurant->currency? $this->restaurant->currency->code: "KWD",
                "price" => $this->item_price,
                "itemCondition" => "https://schema.org/NewCondition",
                "availability" => "https://schema.org/InStock"
            ]
        ];

        return $data;
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert || isset($changedAttributes['item_name'])) {

            if ($this->restaurant->sitemap_require_update == 0) {
                $this->restaurant->sitemap_require_update = 1;

                Restaurant::updateAll([
                    'sitemap_require_update' => $this->restaurant->sitemap_require_update
                ], [
                    'restaurant_uuid' => $this->restaurant_uuid
                ]);
                //$this->restaurant->save (false);
            }
        }

        //check if chatbot enabled

        $isChatbotActivated = RestaurantAddon::find()
            ->joinWith(['addon'])
            ->andWhere([
                'restaurant_addon.restaurant_uuid' => $this->restaurant_uuid,
                "addon.slug" => "chatbot"
            ])
            ->one();

        if (
            $isChatbotActivated && (
                $insert ||
                isset($changedAttributes['item_name']) ||
                isset($changedAttributes['item_name_ar']) ||
                isset($changedAttributes['item_description']) ||
                isset($changedAttributes['item_description_ar'])
            )
        ) {
            $botQueue = RestaurantChatBotQueue::find()
                ->andWhere([
                    'status' => RestaurantChatBotQueue::STATUS_PENDING,
                    'restaurant_uuid' => $this->restaurant_uuid
                ])
                ->one();

            if (!$botQueue) {
                $botQueue = new RestaurantChatBotQueue;
                $botQueue->status = RestaurantChatBotQueue::STATUS_PENDING;
                $botQueue->restaurant_uuid = $this->restaurant_uuid;
                $botQueue->save();
            }
        }

        if ($insert) {
            $this->restaurant->updateCounters([
                'total_items' => 1
            ]);

            if ($this->item_type == self::TYPE_SIMPLE) {
                $inventory = new RestaurantInventory;
                $inventory->restaurant_uuid = $this->restaurant_uuid;
                $inventory->item_uuid = $this->item_uuid;
                $inventory->stock_quantity = $this->stock_qty;
                if (!$inventory->save()) {
                    Yii::error(print_r($inventory->errors, true));
                    return false;
                }
            }
        }

        //Send event to Segment

        //not firing on insert as it will not have all data yet

        if (!$insert && $this->scenario == self::SCENARIO_UPDATE_STOCK) {
            $this->trackEvents($insert);
        }

        //if stock_qty changed

        if (
            !$insert &&
            $this->track_quantity && (
                isset($changedAttributes['stock_qty']) ||
                isset($changedAttributes['track_quantity'])
            )
        ) {
            Yii::$app->eventManager->track('Inventory Updated', [
                'product_id' => $this->item_uuid,
                'item_uuid' => $this->item_uuid,
                'previous_stock' => isset($changedAttributes['stock_qty'])?$changedAttributes['stock_qty']: null,
                'updated_stock' => $this->stock_qty
            ], null, $this->restaurant_uuid);

            if ($this->item_type == self::TYPE_SIMPLE) {
                $this->settleStock();
            }

            /*$model = new RestockHistory;
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->inventory_uuid = $inventory->inventory_uuid;
            $model->restocked_quantity = $this->stock_qty - $changedAttributes['stock_qty'];
            $model->restocked_at = date("Y-m-d");

            if (!$model->save()) {
                Yii::error(print_r($model->errors, true));
            }*/
        }

        return true;
    }

    /**
     * @param $insert
     * @return void
     */
    public function trackEvents($insert) {

        $images = (int)$this->getItemImages()->count();
        $vImages = (int)$this->getVariantImages()->count();

        $category = $this->getCategory()->one();

        $props = [
            'item_uuid' => $this->item_uuid,
            'item_name' => $this->item_name,
            'item_name_ar' => $this->item_name_ar,
            'item_type' => $this->item_type,
            "number_of_media" => $images + $vImages,
            "category_id" => $category ? $category->category_id : null,
            "category_name" => $category ? $category->title : null,
            "product_name_english" => $this->item_name,
            "product_name_arabic" => $this->item_name_ar,
            "product_description_length_english" => $this->item_description,
            "product_description_length_arabic" => $this->item_description_ar,
            "sort_number" => $this->sort_number,
            "preparation_time_minutes" => $this->prep_time_unit == "min" ? $this->prep_time : null,
            "preparation_time_hours" => $this->prep_time_unit == "hrs" ? $this->prep_time : null,
            "preparation_time_days" => $this->prep_time_unit == "day" ? $this->prep_time : null,
            "seo_page_title_english" => $this->item_meta_title,
            "seo_page_title_arabic" => $this->item_meta_title_ar,
            "meta_tag_description_length_english" => strlen($this->item_meta_description),
            "meta_tag_description_length_arabic" => strlen($this->item_meta_description_ar),
            "sku_id" => $this->sku,
            "barcode_id" => $this->barcode,
            "quantity_tracked" => $this->track_quantity,
            "stock_quantity" => $this->stock_qty,
            "is_physical_product" => $this->shipping,
            "number_of_options" => $this->getOptions()->count(),
            "variant_applicable" => $this->item_type == self::TYPE_CONFIGURABLE,
        ];

        if ($insert) {

            if ($this->item_status == self::ITEM_STATUS_PUBLISH) {
                Yii::$app->eventManager->track("Item Published", $props, null, $this->restaurant_uuid);
            }

            Yii::$app->eventManager->track('Item Added', $props, null, $this->restaurant_uuid);

            //check if first product

            $count = Item::find()
                ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
                ->count();

            if ($count == 1) {
                Yii::$app->eventManager->track('Store Setup Step Complete', [
                    'step_name' => "Item Added",
                    'step_number' => 2
                ], null, $this->restaurant_uuid);

                Yii::$app->eventManager->track('Onboard Step Complete', [
                    'step_name' => "Item Added",
                    'step_number' => 4
                ], null, $this->restaurant_uuid);

                $this->restaurant->checkOnboardCompleted();
            }

        } else {
            Yii::$app->eventManager->track('Item Updated', $props, null, $this->restaurant_uuid);
        }
    }

    /**
     * increase stock_qty
     * @param type $qty
     */
    public function increaseStockQty($qty)
    {
        if($this->track_quantity) {
            $props = [
                "item_uuid" => $this->item_uuid,
                "previous_stock" => $this->stock_qty,
                "updated_stock" => $this->stock_qty + $qty
            ];

            Yii::$app->eventManager->track("Inventory Updated", $props, null, $this->restaurant_uuid);
        }

        if ($this->track_quantity)
            $this->stock_qty += $qty;

        $this->unit_sold -= $qty;

        self::updateAll([
            'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_uuid' => $this->item_uuid
        ]);

        $this->settleStock();
    }

    /**
     * decrease stock_qty
     * @param type $qty
     */
    public function decreaseStockQty($qty)
    {
        if($this->track_quantity) {
            $props = [
                "item_uuid" => $this->item_uuid,
                "previous_stock" => $this->stock_qty,
                "updated_stock" => $this->stock_qty - $qty
            ];
            Yii::$app->eventManager->track("Inventory Updated", $props, null, $this->restaurant_uuid);
        }

        if ($this->track_quantity)
            $this->stock_qty -= $qty;

        $this->unit_sold += $qty;

        self::updateAll([
            'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_uuid' => $this->item_uuid
        ]);

        $this->settleStock();
    }

    public function settleStock() {
        //find inventory

        $inventory = RestaurantInventory::find()
            ->andWhere(['item_uuid' => $this->item_uuid])
            ->one();

        if (!$inventory) {
            $inventory = new RestaurantInventory;
            $inventory->restaurant_uuid = $this->restaurant_uuid;
            $inventory->item_uuid = $this->item_uuid;
        }

        $inventory->stock_quantity = $this->stock_qty;
        $inventory->restocked_at = date("Y-m-d");
        if (!$inventory->save()) {
            Yii::error(print_r($inventory->errors, true));
            return false;
        }
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
//        foreach ($this->getItemImages()->all() as  $itemImage) {
//          $itemImage->delete();
//        }

        foreach ($imagesPath as $key => $path) {

            $filename = Yii::$app->security->generateRandomString();

            $itemName = str_replace(' ', '', $this->item_name);

            try {
                $result = Yii::$app->cloudinaryManager->upload(
                    $path['file'],
                    [
                        'public_id' => "restaurants/" . $this->restaurant_uuid . "/items/" . $filename
                    ]
                );

                if ($result || count($result) > 0) {
                    $item_image_model = new ItemImage();
                    $item_image_model->item_uuid = $this->item_uuid;
                    $item_image_model->product_file_name = basename($result['url']);
                    $item_image_model->sort_number = isset($path['sort_number'])? $path['sort_number']: 0;
                    $item_image_model->save(false);
                }

                unlink($path['file']);

            } catch (\Cloudinary\Error $err) {
                //todo: notify vendor
                
                //Yii::error("Error when uploading item's image to Cloudinry: " . json_encode($err));
                //Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($imagesPath));

            }
        }
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete()
    {
        //RestaurantInventory::deleteAll(['item_uuid' => $this->item_uuid]);

        ItemImage::deleteAll(['item_uuid' => $this->item_uuid]);

        ItemVideo::deleteAll(['item_uuid' => $this->item_uuid]);

        CategoryItem::deleteAll(['item_uuid' => $this->item_uuid]);

        $variants = $this->getItemVariants()->all();

        foreach ($variants as $variant) {

            ItemVariantOption::deleteAll(['item_variant_uuid' => $variant->item_variant_uuid]);
            ItemVariantImage::deleteAll(['item_variant_uuid' => $variant->item_variant_uuid]);

            $variant->delete();
        }

        $this->restaurant->updateCounters([
            'total_items' => -1
        ]);

        return parent::beforeDelete();
    }

    /**
     * @param $file_name
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findItemImageByFileName($file_name)
    {
        return ItemImage::find()
            ->andWhere(['item_uuid' => $this->item_uuid, 'product_file_name' => $file_name])
            ->one();
    }

    /**
     * @param $model
     * @return ActiveQuery
     */
    public function getRestaurantInventory($model = 'common\models\RestaurantInventory')
    {
        return $this->hasOne($model::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemVariant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariants($model = 'common\models\ItemVariant')
    {
        return $this->hasMany($model::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems($model = 'common\models\CategoryItem')
    {
        return $this->hasMany($model::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[CategoryItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory($modelClass = "\common\models\Category")
    {
        return $this->hasMany($modelClass::className(), ['category_id' => 'category_id'])->via('categoryItems');
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories($modelClass = "\common\models\Category")
    {
        return $this->hasMany($modelClass::className(), ['category_id' => 'category_id'])
            ->viaTable('category_item', ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id'])->via('restaurant');
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions($modelClass = "\common\models\Option")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->orderBy("sort_number");
    }

    /**
     * Gets query for [[ItemVideos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVideos($model = 'common\models\ItemVideo')
    {
        return $this->hasMany($model::className(), ['item_uuid' => 'item_uuid'])
            ->orderBy('sort_number');
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages($model = 'common\models\ItemImage')
    {
        return $this->hasMany($model::className(), ['item_uuid' => 'item_uuid'])
            ->orderBy('sort_number');
    }

    /**
     * @param $model
     * @return \yii\db\ActiveQuery
     */
    public function getVariantImages($model = 'common\models\ItemVariantImage')
    {
        return $this->hasMany($model::className(), ['item_uuid' => 'item_uuid'])
            ->orderBy('sort_number');
    }

    /**
     * @param $model
     * @return \yii\db\ActiveQuery
     */
    public function getItemImage($model = 'common\models\ItemImage')
    {
        return $this->hasOne($model::className(), ['item_uuid' => 'item_uuid'])
            ->orderBy('sort_number');
    }

    /**
     * Gets query for [[Options]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOptions($modelClass = "\common\models\ExtraOption")
    {
        return $this->hasMany($modelClass::className(), ['option_id' => 'option_id'])
            ->via('options');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
                'IN',
                'order.order_status', [
                    Order::STATUS_ACCEPTED,
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE
                ]
            ])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     */
    public function getSoldUnitsInSpecifcDate($start_date = null, $end_date = null, $modelClass = "\common\models\OrderItem")
    {
        $query = $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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

        if ($start_date && $end_date) {
            $query->andWhere(['between', 'order.order_created_at', $start_date, $end_date]);
        }

        return $query->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getTodaySoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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
            ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getThisWeekSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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
            ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getCurrentMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->sum('qty');
    }

    /**
     * Gets query for [[Options]].
     *
     */
    public function getLastThreeMonthSoldUnits($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->joinWith('order')
            ->andWhere([
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
            ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
            ->sum('qty');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['order_uuid' => 'order_uuid'])->via('orderItems');
    }

    /**
     * @return query\ItemQuery
     */
    public static function find()
    {
        return new query\ItemQuery(get_called_class());
    }
}
