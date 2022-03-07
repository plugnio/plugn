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
 * @property int|null $track_quantity
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
            [['stock_qty', 'track_quantity', 'weight'], 'integer'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'stock_qty' => Yii::t('app', 'Stock Qty'),
            'track_quantity' => Yii::t('app', 'Track Quantity'),
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
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemUu()
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
}
