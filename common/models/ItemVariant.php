<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
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
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $itemUu
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
            [['stock_qty', 'weight'], 'integer'],
            [['price', 'compare_at_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_variant_uuid'], 'string', 'max' => 60],
            [['item_uuid'], 'string', 'max' => 100],
            [['sku', 'barcode'], 'string', 'max' => 255],
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

        $itemVariantImageIds = [];

        foreach ($this->images as $img) {

            //empty data

            if (!$img['product_file_name']) {
                continue;
            }

            //for old images

            if(isset($img['item_variant_image_uuid'])) {
                $itemVariantImageIds[] = $img['item_variant_image_uuid'];
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
                    $item_image_model->save(false);

                    $itemVariantImageIds[] = $item_image_model->item_variant_image_uuid;

                } catch (\Cloudinary\Error $err) {
                    Yii::error("Error when uploading item's image to Cloudinry: imagesPath Value " . json_encode($this->images));
                    return false;
                }
        }

        //remove deleted/extra images

        ItemVariantImage::deleteAll([
            'AND',
            ['item_variant_uuid' => $this->item_variant_uuid],
            ['NOT IN', 'item_variant_image_uuid', $itemVariantImageIds]
        ]);

        return true;
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

    /**
     * Gets query for [[ItemVariantOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantOptions()
    {
        return $this->hasMany(ItemVariantOption::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * Gets query for [[ItemVariantOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantImages()
    {
        return $this->hasMany(ItemVariantImage::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }
}
