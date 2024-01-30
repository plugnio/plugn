<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "order_item".
 *
 * @property int $order_item_id
 * @property int $order_uuid
 * @property string $restaurant_uuid
 * @property string $item_uuid
 * @property string $item_variant_uuid
 * @property string $item_name
 * @property string $item_name_ar
 * @property float $item_price
 * @property float $item_unit_price
 * @property int|null $qty
 * @property float $weight
 * @property float $length
 * @property float $height
 * @property float $width
 * @property boolean $shipping
 * @property string|null $customer_instruction
 * @property datetime $order_item_created_at
 * @property datetime $order_item_updated_at
 * @property Item $item
 * @property Restaurant $restaurant
 * @property Order $order
 * @property OrderItemExtraOptions[] $orderItemExtraOptions
 */
class OrderItem extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            //'item_name', 'item_price',
            [['order_uuid', 'restaurant_uuid', 'qty'], 'required'],
            [['qty'], 'integer', 'min' => 0],
            ['qty', 'validateQty'],
            [['order_uuid'], 'string', 'max' => 40],
            [['shipping'], 'boolean'],
            [['item_price', 'item_unit_price', 'weight', 'length','height', 'width'], 'number', 'min' => 0],
            [['item_uuid'], 'checkIfItemBelongToRestaurant'],
            [['item_variant_uuid'], 'checkIfVariantBelongToRestaurant'],
            [['item_uuid'], 'string', 'max' => 300],
            [['item_name', 'item_name_ar', 'customer_instruction'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['item_variant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => ItemVariant::className(), 'targetAttribute' => ['item_variant_uuid' => 'item_variant_uuid']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'order_item_created_at',
                'updatedAtAttribute' => 'order_item_updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_item_id' => Yii::t('app','Order Item ID'),
            'order_uuid' => Yii::t('app','Order ID'),
            'restaurant_uuid' => Yii::t('app','Store ID'),
            'item_uuid' => Yii::t('app','Item Uuid'),
            'item_variant_uuid' => Yii::t('app','Item Variant Uuid'),
            'item_name' => Yii::t('app','Item Name'),
            'item_name_ar' => Yii::t('app','Item Name in Arabic'),
            'item_price' => Yii::t('app','Item Price'),
            'item_unit_price' => Yii::t('app','Item Unit Price'),
            'qty' => Yii::t('app','Quantity'),
            'weight' => Yii::t('app','Weight'),
            'length' => Yii::t('app','Length'),
            'height' => Yii::t('app','Height'),
            'width'  => Yii::t('app','Width'),
            'shipping'=> Yii::t('app','Shipping'),
            'customer_instruction' => Yii::t('app','Instructions'),
        ];
    }

    /**
     * @param $attribute
     * @return bool|void
     */
    public function validateQty($attribute)
    {
        if(!$this->item || !$this->item->track_quantity) {
            return true;
        }

        if($this->item->item_type == Item::TYPE_SIMPLE) {
            if($this->qty > $this->item->stock_qty) {
                $this->addError($attribute, Yii::t('app', 'Item out of stock'));
            }
        } else {
            if(!$this->variant) {
                $this->addError($attribute, Yii::t('app', 'Variant detail missing'));
            }
            else if($this->qty > $this->variant->stock_qty)
            {
                $this->addError($attribute, Yii::t('app', 'Variant out of stock'));
            }
        }
    }

    /**
     * Check if item belongs to restaurant
     * @param type $attribute
     */
    public function checkIfItemBelongToRestaurant($attribute)
    {
        $isItemBelongToRestaurant = $this->order ? Item::find()
            ->where([
                'restaurant_uuid' => $this->order->restaurant_uuid,
                'item_uuid' => $this->item_uuid
            ])
            ->one(): null;

        if (!$isItemBelongToRestaurant) {
            $this->addError($attribute, Yii::t('yii', '{attribute} is invalid', [
                'attribute' => $attribute
            ]));
        }
        else if ($isItemBelongToRestaurant->item_status == Item::ITEM_STATUS_UNPUBLISH)
        {
            $this->addError($attribute, Yii::t('app', 'Sorry, the selected item is no longer available.'));
        }
    }

    /**
     * Check if variant belongs to restaurant
     * @param type $attribute
     */
    public function checkIfVariantBelongToRestaurant($attribute)
    {
        $isItemBelongToRestaurant = $this->order? ItemVariant::find()
            ->where([
                'item_uuid' => $this->item_uuid,
                'item_variant_uuid' => $this->item_variant_uuid
            ])
            ->one(): null;

        if (!$isItemBelongToRestaurant)
            $this->addError($attribute, Yii::t('yii', '{attribute} is invalid', [
                'attribute' => $attribute
            ]));
    }

    /**
     * Calculate order item total price => (item price + extra optns price)
     */
    public function calculateOrderItemPrice()
    {
        $item = $this->getItem ()->one();

        if($this->variant) {
            $totalPrice = $this->variant->price;
        } else if($item) {
            $totalPrice = $item->item_price;
        } else if($this->item_unit_price) {
            $totalPrice = $this->item_unit_price;
        } else {
            $totalPrice = $this->item_price;
        }

        foreach ($this->getOrderItemExtraOptions()->asArray()->all() as $extraOption)
            $totalPrice += $extraOption['extra_option_price'];

        //convert from store currency to order currency if not same

        /*if($this->order->currency && $this->restaurant->currency->code != $this->order->currency_code)
        {
            $totalPrice = ($totalPrice / $this->restaurant->currency->rate) * $this->order->currency->rate;
        }*/

        $this->item_unit_price = round($totalPrice, $this->order->currency->decimal_place);

        return round($this->item_unit_price * $this->qty, $this->order->currency->decimal_place);
    }

    /**
     * delete related data before delete
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete() {

        if ($this->item) {
            $this->item->increaseStockQty($this->qty);
        }

        if ($this->variant) {
            $this->variant->increaseStockQty($this->qty);
        }

        $orderItemsExtraOption = OrderItemExtraOption::find()
            ->where(['order_item_id' => $this->order_item_id])
            ->all();

        if($orderItemsExtraOption) {

          foreach ($orderItemsExtraOption as $orderItemExtraOption)
             $orderItemExtraOption->delete();
        }

        return parent::beforeDelete();
    }

    /**
     * @return false|void
     */
    public function afterDelete() {
        $order = Order::findOne($this->order_uuid);

        if ($order) {
            return $order->updateOrderTotalPrice();
        }

        return false;
    }

    /**
     * @param bool $insert
     * @return bool|void
     */
    public function beforeSave($insert) {

        parent::beforeSave($insert);

        $item = Item::findOne($this->item_uuid);
        $order = Order::findOne($this->order_uuid);

        //Update order total price

        $order->updateOrderTotalPrice();

        if(!$this->restaurant_uuid && $this->order) {
            $this->restaurant_uuid = $this->order->restaurant_uuid;
        }

        if ($this->qty == 0) {
            return $this->addError('qty', Yii::t('yii', '{attribute} is invalid', [
                'attribute' => Yii::t('app', 'Quantity')
            ]));
        }

        //Update product inventory

        if ($insert && $item) {

            $this->item_name = $item->item_name;
            $this->item_name_ar = $item->item_name_ar;

            $this->shipping = $item->shipping;
            $this->width  = $item->width;
            $this->length = $item->length;
            $this->height = $item->height;
            $this->weight = $item->weight;            
        }

        $this->item_price = $this->calculateOrderItemPrice();

        //if custom item

        if(!$item && $this->item_price) {
            return true;
        }

        //stock validation : already doing in attributes

        if($this->item && $this->item->track_quantity) {

            if ($insert) {

                if($this->item->item_type == Item::TYPE_SIMPLE) {
                    if ($this->qty > $this->item->stock_qty) {
                        return $this->addError('qty', Yii::t('app', "{name} is currently out of stock and unavailable.", [
                            'name' => $this->item->item_name
                        ]));
                    }
                }
                else
                {
                    if(!$this->variant) {
                        return $this->addError("variant_item_uuid", Yii::t('app', 'Variant detail missing'));
                    }
                    else if ($this->qty > $this->variant->stock_qty)
                    {
                        return $this->addError('qty', Yii::t('app', "{name} is currently out of stock and unavailable.", [
                            'name' => $this->item->item_name
                        ]));
                    }
                }

            } else {
                if($this->item->item_type == Item::TYPE_SIMPLE) {
                    if ($this->qty > $this->item->stock_qty + $this->getOldAttribute('qty')) {
                        return $this->addError('qty', Yii::t('app', "{name} is currently out of stock and unavailable.", [
                            'name' => $this->item->item_name
                        ]));
                    }
                }
                else
                {
                    if(!$this->variant) {
                        return $this->addError("variant_item_uuid", Yii::t('app', 'Variant detail missing'));
                    }
                    else if ($this->qty > $this->variant->stock_qty + $this->getOldAttribute('qty') )
                    {
                        return $this->addError('qty', Yii::t('app', "{name} is currently out of stock and unavailable.", [
                            'name' => $this->item->item_name
                        ]));
                    }
                }
            }
        }

        //Update product inventory
        
        if ($insert) {
          if ($item) {
              $this->item_name = $item->item_name;
              $this->item_name_ar = $item->item_name_ar;
          } else {
              return false; //if custom item
          }
        }

        $this->item_price = $this->calculateOrderItemPrice();

        //$this->item_unit_price = $this->item_price / $this->qty;

        return true;
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes) {

      if($this->item_uuid != null) {

        $item = Item::findOne($this->item_uuid);

        if (!$insert && isset($changedAttributes['qty']) && $item && $item->track_quantity) {

            $item->increaseStockQty($changedAttributes['qty']);
            $item->decreaseStockQty($this->qty);

            if ($this->variant) {
                $this->variant->increaseStockQty($changedAttributes['qty']);
                $this->variant->decreaseStockQty($this->qty);
            }
            else
            {
                $orderItemExtraOptions = $this->getOrderItemExtraOptions()
                    ->with(['extraOption'])
                    ->all();

                foreach ($orderItemExtraOptions as $orderItemExtraOption) {
                    $orderItemExtraOption->extraOption->increaseStockQty($changedAttributes['qty']);
                    $orderItemExtraOption->extraOption->decreaseStockQty($this->qty);
                }
            }
        }
      }

        $order = Order::findOne($this->order_uuid);

        if ($order)
            $order->updateOrderTotalPrice();

        return parent::afterSave($insert, $changedAttributes);
    }

    public function getOrderExtraOptionsText()
    {
        $value = [];
        if (count($this->orderItemExtraOptions) > 0) {
            foreach ($this->orderItemExtraOptions as $extra) {
                $value[] = $extra['extra_option_name'];
            }
            if (count($value) > 0) {
                return implode(',', $value);
            }

            return '(NOT SET)';
        }
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'item_price' => function ($order) {
                return (float)$order->item_price;
            },
            'item_unit_price' => function ($order) {
                return (float)$order->item_unit_price;
            }
        ]);
    }

    /**
     * @return string[]
     */
    public function extraFields() {
        return [
            'currency',
            'orderItemExtraOptions',
            'itemImage',
            'itemVariantImage',
            'image',
            'item',
            'variant',
            'refundedQty'
        ];
    }

    /**
     * Gets query for [[RefundedItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedQty($modelClass = "\common\models\RefundedItem") {
        return $this->getRefundedItem($modelClass)
            ->sum('qty');
    }

    /**
     * Gets query for [[RefundedItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItem($modelClass = "\common\models\RefundedItem") {
        return $this->hasMany($modelClass::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item") {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[Variant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariant($modelClass = "\common\models\ItemVariant") {
        return $this->hasOne($modelClass::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->item_variant_uuid? $this->getItemVariantImage():
            $this->getItemImage();
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImage($modelClass = "\common\models\ItemImage")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemVariantImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemVariantImage($modelClass = "\common\models\ItemVariantImage")
    {
        return $this->hasOne($modelClass::className(), ['item_variant_uuid' => 'item_variant_uuid']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order") {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['code' => 'currency_code'])
            ->via('order');
    }

    /**
     * Gets query for [[OrderItemExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\common\models\OrderItemExtraOption") {
        return $this->hasMany($modelClass::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * @return query\OrderItemQuery
     */
    public static function find()
    {
        return new query\OrderItemQuery(get_called_class ());
    }
}
