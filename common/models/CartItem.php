<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "cart_item".
 *
 * @property string $cart_item_uuid
 * @property string $cart_uuid
 * @property string $item_uuid
 * @property string|null $item_variant_uuid
 * @property string $key
 * @property int $qty
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property CartItemOption[] $cartItemOptions
 * @property Cart $cart
 * @property Item $item
 * @property ItemVariant $itemVariant
 */
class CartItem extends \yii\db\ActiveRecord
{
    public $itemOptions;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //'cart_item_uuid',  'key', 'created_at'
            [['cart_uuid', 'item_uuid'], 'required'],
            [['qty'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['cart_item_uuid', 'cart_uuid', 'item_uuid', 'item_variant_uuid'], 'string', 'max' => 60],
            [['key'], 'string', 'max' => 255],
            [['cart_item_uuid'], 'unique'],
            [['cart_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_uuid' => 'cart_uuid']],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['item_variant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => ItemVariant::class, 'targetAttribute' => ['item_variant_uuid' => 'item_variant_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'cart_item_uuid',
                ],
                'value' => function () {
                    if (!$this->cart_item_uuid) {
                        $this->cart_item_uuid = 'cart_item_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->cart_item_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!parent::afterSave($insert, $changedAttributes)) {
            return false;
        }

        if (!$this->key) {
            $cart_hash_seed = $this->item_uuid;

            foreach($this->cartItemOptions as $cartItemOption) {
                if($cartItemOption->value_id) {
                    $cart_hash_seed .= ":" + $cartItemOption->value_id;
                } else {
                    $cart_hash_seed .= ":" + $cartItemOption->value;
                }

                $cartItemOption = new CartItemOption();
                $cartItemOption->cart_item_uuid = $this->cart_item_uuid;
                $cartItemOption->option_id = $cartItemOption->option_id;
                $cartItemOption->value_id = $cartItemOption->value_id;
                $cartItemOption->value = $cartItemOption->value;
                if(!$cartItemOption->save()) {
                    Yii::error("Failed to save cart item option: " . json_encode($cartItemOption->getErrors()));

                    throw new ServerErrorHttpException("Failed to save cart item option: " . json_encode($cartItemOption->getErrors()));

                   // return false;
                }
            }

            self::updateAll(["key" => md5($cart_hash_seed)],
                ["cart_item_uuid" => $this->cart_item_uuid]);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cart_item_uuid' => Yii::t('app', 'Cart Item Uuid'),
            'cart_uuid' => Yii::t('app', 'Cart Uuid'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'item_variant_uuid' => Yii::t('app', 'Item Variant Uuid'),
            'key' => Yii::t('app', 'Key'),
            'qty' => Yii::t('app', 'Qty'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return array the active query used by this AR class.
     */
    private function extraFields() {
        return [
            "cartItemOptions",
            "item",
            "itemVariant",
        ];
    }

    /**
     * Gets query for [[CartItemOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartItemOptions()
    {
        return $this->hasMany(CartItemOption::class, ['cart_item_uuid' => 'cart_item_uuid']);
    }

    /**
     * Gets query for [[CartUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(Cart::class, ['cart_uuid' => 'cart_uuid']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemVariant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariant()
    {
        return $this->hasOne(ItemVariant::class, ['item_variant_uuid' => 'item_variant_uuid']);
    }
}
