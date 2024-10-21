<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "item_variant".
 *
 * @property string $item_variant_uuid
 * @property string|null $item_uuid
 * @property int|null $stock_qty
 * @property string|null $sku
 * @property string|null $barcode
 * @property float|null $price
 * @property float|null $compare_at_price
 * @property int|null $weight
 * @property number|null $length
 * @property number|null $width
 * @property number|null $height
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $item
 * @property ItemVariantOption[] $itemVariantOptions
 */
class ItemVariant extends \yii\db\ActiveRecord
{
    public $images = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_variant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_qty'], 'integer'],
            [['price', 'compare_at_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_variant_uuid'], 'string', 'max' => 60],
            [['item_uuid'], 'string', 'max' => 100],
            [['sku', 'barcode'], 'string', 'max' => 255],
            [['weight', 'length', 'height', 'width'], 'number', "max" => 8],
            [['item_variant_uuid'], 'unique'],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
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
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'item_variant_uuid',
                ],
                'value' => function () {
                    if (!$this->item_variant_uuid) {
                        $this->item_variant_uuid = 'item_variant_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->item_variant_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        $fields = parent::fields();

        return array_merge ($fields, [
            'itemVariantOptions',
            'itemVariantImages'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'stock_qty' => Yii::t('app', 'Stock Qty'),
            'sku' => Yii::t('app', 'Sku'),
            'barcode' => Yii::t('app', 'Barcode'),
            'price' => Yii::t('app', 'Price'),
            'compare_at_price' => Yii::t('app', 'Compare At Price'),
            'weight' => Yii::t('app', 'Weight'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param bool $insert
     * @return false|void
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert))
        {
            return false;
        }

        return true;
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return false|void
     * @throws \yii\base\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $itemVariantImageIds = [];

        foreach ($this->images as $img) {

            //empty data

            if (!$img['product_file_name']) {
                continue;
            }

            //for old images

            if(isset($img['item_variant_image_uuid'])) {
                $itemVariantImageIds[] = $img['item_variant_image_uuid'];

                $item_image_model = ItemVariantImage::findOne($img['item_variant_image_uuid']);
                $item_image_model->sort_number = isset($img['sort_number'])? $img['sort_number']: 0;
                $item_image_model->save(false);

                continue;
            }

            try {
                $url = Yii::$app->temporaryBucketResourceManager->getUrl($img['product_file_name']);

                $filename = Yii::$app->security->generateRandomString();

                $data[] = $result = Yii::$app->cloudinaryManager->upload(
                    $url,
                    [
                        'public_id' => "restaurants/" . $this->item->restaurant_uuid . "/items/" . $filename
                    ]
                );

                $item_image_model = new ItemVariantImage();
                $item_image_model->item_uuid = $this->item_uuid;
                $item_image_model->item_variant_uuid = $this->item_variant_uuid;
                $item_image_model->product_file_name = basename($result['url']);
                $item_image_model->sort_number = isset($img['sort_number'])? $img['sort_number']: 0;

                if(!$item_image_model->save(false))
                {
                    Yii::error("Error when uploading item's image to Cloudinry " . json_encode($this->images));
                }

                $itemVariantImageIds[] = $item_image_model->item_variant_image_uuid;

            } catch (\Cloudinary\Error $err) {
                Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($this->images));

                //todo: notify vendor?

                return false;
            }
        }

        //remove deleted/extra images

        ItemVariantImage::deleteAll([
            'AND',
            ['item_variant_uuid' => $this->item_variant_uuid],
            ['NOT IN', 'item_variant_image_uuid', $itemVariantImageIds]
        ]);

        //if stock_qty changed

        if(
            !$insert &&
            $this->item->track_quantity &&
            isset($changedAttributes['stock_qty'])
        )  {
            Yii::$app->eventManager->track('Inventory Updated', [
                'product_id' => $this->item_uuid,
                'item_variant_uuid' => $this->item_variant_uuid,
                'item_uuid' => $this->item_uuid,
                'previous_stock' => $changedAttributes['stock_qty'],
                'updated_stock' => $this->stock_qty
            ], null, $this->item->restaurant_uuid);

            $this->settleStock();

            /*$model = new RestockHistory;
            $model->restaurant_uuid = $this->item->restaurant_uuid;
            $model->inventory_uuid = $inventory->inventory_uuid;
            $model->restocked_quantity = $this->stock_qty - $changedAttributes['stock_qty'];
            $model->restocked_at = date("Y-m-d");

            if (!$model->save()) {
                Yii::error(print_r($model->errors, true));
            }*/
        }
    }

    /**
     * increase stock_qty
     * @param type $qty
     */
    public function increaseStockQty($qty)
    {
        if (!$this->item->track_quantity)
            return true;

        $this->stock_qty += $qty;

        //$this->unit_sold -= $qty;

        self::updateAll([
            //'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_variant_uuid' => $this->item_variant_uuid
        ]);

        Yii::$app->eventManager->track('Inventory Updated', [
            'product_id' => $this->item_uuid,
            'item_variant_uuid' => $this->item_variant_uuid,
            'item_uuid' => $this->item_uuid,
            'previous_stock' => $qty,
            'updated_stock' => $this->stock_qty
        ], null, $this->item->restaurant_uuid);

        $this->settleStock();
    }

    /**
     * decrease stock_qty
     * @param type $qty
     */
    public function decreaseStockQty($qty)
    {
        if (!$this->item->track_quantity)
            return true;

        $this->stock_qty -= $qty;

        //$this->unit_sold += $qty;

        self::updateAll([
            //'unit_sold' => $this->unit_sold,
            'stock_qty' => $this->stock_qty
        ], [
            'item_variant_uuid' => $this->item_variant_uuid
        ]);

        Yii::$app->eventManager->track('Inventory Updated', [
            'product_id' => $this->item_uuid,
            'item_variant_uuid' => $this->item_variant_uuid,
            'item_uuid' => $this->item_uuid,
            'previous_stock' => $qty,
            'updated_stock' => $this->stock_qty
        ], null, $this->item->restaurant_uuid);

        $this->settleStock();
    }

    /**
     * @return false|void
     */
    public function settleStock() {

        //find inventory

        $inventory = RestaurantInventory::find()
            ->andWhere(['item_variant_uuid' => $this->item_variant_uuid])
            ->one();

        if (!$inventory) {
            $inventory = new RestaurantInventory;
            $inventory->restaurant_uuid = $this->item->restaurant_uuid;
            $inventory->item_variant_uuid = $this->item_variant_uuid;
        }

        $inventory->stock_quantity = $this->stock_qty;
        $inventory->restocked_at = date("Y-m-d");
        if (!$inventory->save()) {
            Yii::error(print_r($inventory->errors, true));
            return false;
        }
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * @param $model
     * @return ActiveQuery
     */
    public function getRestaurantInventory($model = 'common\models\RestaurantInventory')
    {
        return $this->hasOne($model::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * Gets query for [[ItemVariantOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantOptions($modelClass = "\common\models\ItemVariantOption")
    {
        return $this->hasMany($modelClass::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * @param $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantExtraOptions($modelClass = "\common\models\ExtraOption")
    {
        return $this->hasMany($modelClass::className(), ['extra_option_id' => 'extra_option_id'])
            ->via('itemVariantOptions');
    }

    /**
     * Gets query for [[ItemVariantOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantImages($modelClass = "\common\models\ItemVariantImage")
    {
        return $this->hasMany($modelClass::className(), ['item_variant_uuid' => 'item_variant_uuid'])
            ->orderBy('sort_number');
    }
}
