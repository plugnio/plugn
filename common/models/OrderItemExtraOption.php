<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item_extra_option".
 *
 * @property int $order_item_extra_option_id
 * @property int $order_item_id
 * @property int $option_id
 * @property int $extra_option_id
 * @property string $option_name
 * @property string $option_name_ar
 * @property string $extra_option_name
 * @property string $extra_option_name_ar
 * @property float $extra_option_price
 *
 * @property ExtraOption $extraOption
 * @property Restaurant $restaurant
 * @property Order $order
 * @property OrderItem $orderItem
 * @property Item $Item
 */
class OrderItemExtraOption extends \yii\db\ActiveRecord {

    const SCENARIO_CREATE_ORDER_ITEM_EXTRA_OPTION_BY_ADMIN = 'manual';


    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order_item_extra_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            //'extra_option_name', 'extra_option_price', 
            [['order_item_id', 'qty'], 'required'],//extra_option_id
            [['qty'], 'required', 'except' => self::SCENARIO_CREATE_ORDER_ITEM_EXTRA_OPTION_BY_ADMIN],
            [['qty'], 'default', 'value' => 1 , 'on' =>  self::SCENARIO_CREATE_ORDER_ITEM_EXTRA_OPTION_BY_ADMIN],
            ['qty', 'validateQty'],
            [['order_item_id', 'extra_option_id','qty'], 'integer', 'min' => 0],
            [['extra_option_id'], 'checkIfExtraOptionBelongToItem'],
            [['extra_option_price'], 'number', 'min' => 0],
            [['option_name', 'option_name_ar', 'extra_option_name', 'extra_option_name_ar'], 'string', 'max' => 255],
            [['option_id'], 'exist', 'skipOnError' => false, 'targetClass' => Option::className(), 'targetAttribute' => ['option_id' => 'option_id']],
            [['extra_option_id'], 'exist', 'skipOnError' => false, 'targetClass' => ExtraOption::className(), 'targetAttribute' => ['extra_option_id' => 'extra_option_id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderItem::className(), 'targetAttribute' => ['order_item_id' => 'order_item_id']],
        ];
    }

    public function validateQty($attribute)
    {
        if(
            !$this->orderItem->item || //if custom items or deleted (have nothing to compare to)
            !$this->orderItem->item->track_quantity || //if tracking disabled
            $this->orderItem->item->item_type == Item::TYPE_CONFIGURABLE //not tracking option stock for configurable items
        ) {
            return true;
        }

        if($this->qty < $this->extraOption->stock_qty)
            $this->addError($attribute, 'Out of stock');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_item_extra_option_id' => 'Order Item Extra Option ID',
            'order_item_id' => 'Order Item ID',
            'extra_option_id' => 'Extra Option ID',
            'extra_option_name' => 'Extra Option Name',
            'qty' => 'Quantity',
            'extra_option_name_ar' => 'Extra Option Name Ar',
            'extra_option_price' => 'Extra Option Price',
        ];
    }

    public function afterSave($insert, $changedAttributes) {

        $order_model = Order::findOne($this->orderItem->order_uuid);

        if ($order_model)
            $order_model->updateOrderTotalPrice();

        $order_item_model = OrderItem::findOne($this->order_item_id);
        $order_item_model->item_price = $order_item_model->calculateOrderItemPrice();
        $order_item_model->save(false);

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete() {

        $order_model = Order::findOne($this->orderItem->order_uuid);

        if ($order_model) {
            return $order_model->updateOrderTotalPrice();
        }

        return false;
    }

    /**
     * @param type $attribute
     */
    public function checkIfExtraOptionBelongToItem($attribute) {
        $extra_option_model = ExtraOption::findOne($this->extra_option_id);

        if ($extra_option_model) {
            if ($this->orderItem->item_uuid != $extra_option_model->option->item_uuid)
                $this->addError($attribute, 'Extra Option Uuid is invalid');
        }else {
            $this->addError($attribute, 'Extra Option Uuid is invalid');
        }
    }

    public function beforeSave($insert) {

        $extra_option_model = ExtraOption::findOne($this->extra_option_id);

        if ($insert) {

            if($this->option) {
                $this->option_id = $this->option->option_id;
                $this->option_name = $this->option->option_name;
                $this->option_name_ar = $this->option->option_name_ar;
                $this->extra_option_price = 0;
            }

            if ($extra_option_model) {

                /**
                 * no variant/ simple product + tracking quantity
                 */
                if(!$this->orderItem->item_variant_uuid && $this->orderItem->item && $this->orderItem->item->track_quantity)
                {
                    if ($extra_option_model->stock_qty !== null && $extra_option_model->stock_qty <= 0)
                        return $this->addError('qty', $extra_option_model->extra_option_name . " is currently out of stock and unavailable.");

                    if ($extra_option_model->stock_qty !== null && $extra_option_model->stock_qty < $this->qty)
                        return $this->addError('qty', $extra_option_model->extra_option_name . " is currently out of stock and unavailable.");

                    if ($this->qty == 0)
                        return $this->addError('qty', "Invalid input");
                }

                //Update stock qty

                $extra_option_model->decreaseStockQty($this->qty);

                $this->extra_option_name = $extra_option_model->extra_option_name;
                $this->extra_option_name_ar = $extra_option_model->extra_option_name_ar;
                $this->extra_option_price = $extra_option_model->extra_option_price;
            }

            if(!$this->option_id && !$this->extra_option_id) {
                return false;
            }

        } else {
            if(!$this->orderItem->item_variant_uuid && $this->orderItem->item && $this->orderItem->item->track_quantity) {
                if ($extra_option_model && $extra_option_model->stock_qty !== null && $extra_option_model->stock_qty >= $this->qty)
                    return $this->addError('qty', $extra_option_model->extra_option_name . " is currently out of stock and unavailable.");
            }
        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete() {

        $extra_option_model = ExtraOption::findOne($this->extra_option_id);

        if ($extra_option_model && $this->option->item->item_type != Item::TYPE_CONFIGURABLE)
            $extra_option_model->increaseStockQty($this->qty); //Update stock qty

        return parent::beforeDelete();
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order") {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid'])->via('orderItem');
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->via('order');
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
     * Gets query for [[ExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOption($modelClass = "\common\models\ExtraOption") {
        return $this->hasOne($modelClass::className(), ['extra_option_id' => 'extra_option_id']);
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption($modelClass = "\common\models\Option") {
        return $this->hasOne($modelClass::className(), ['option_id' => 'option_id']);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem($modelClass = "\common\models\OrderItem") {
        return $this->hasOne($modelClass::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item") {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid'])
            ->via('orderItem');
    }
}
