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
 * @property string $item_uuid
 * @property string $item_name
 * @property string $item_name_ar
 * @property float $item_price
 * @property int|null $qty
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
            [['order_uuid', 'item_uuid', 'qty'], 'required'],
            [['qty'], 'integer', 'min' => 0],
            [['order_uuid'], 'string', 'max' => 40],
            [['item_price'], 'number', 'min' => 0],
            [['item_uuid'], 'checkIfItemBelongToRestaurant'],
            [['item_uuid'], 'string', 'max' => 300],
            [['item_name', 'item_name_ar', 'customer_instruction'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
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
            'order_item_id' => 'Order Item ID',
            'order_uuid' => 'Order ID',
            'restaurant_uuid' => 'Store ID',
            'item_uuid' => 'Item Uuid',
            'item_name' => 'Item Name',
            'item_name_ar' => 'Item Name in Arabic',
            'item_price' => 'Item Price',
            'qty' => 'Quantity',
            'customer_instruction' => 'Instructions',
        ];
    }

    /**
     * Check if item belongs to restaurant
     * @param type $attribute
     */
    public function checkIfItemBelongToRestaurant($attribute) {
        $isItemBelongToRestaurant = Item::find()->where(['restaurant_uuid' => $this->order->restaurant_uuid, 'item_uuid' => $this->item_uuid])->one();

        if (!$isItemBelongToRestaurant)
            $this->addError($attribute, 'Item Uuid is invalid');
        else if ($isItemBelongToRestaurant->item_status == Item::ITEM_STATUS_UNPUBLISH)
            $this->addError($attribute, 'Sorry, the selected item is no longer available.');

    }

    /**
     * Calculate order item total price => (item price + extra optns price)
     */
    public function calculateOrderItemPrice() {

        if($this->item)
          $totalPrice = $this->item->item_price; //5
        else
          $totalPrice = $this->item_price; //5

        foreach ($this->getOrderItemExtraOptions()->asArray()->all() as $extraOption)
            $totalPrice += $extraOption['extra_option_price']; //1

        $totalPrice *= $this->qty; //6*5

        return $totalPrice;
    }

    /**
     * delete related data before delete
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete() {

        if ($this->item)
            $this->item->increaseStockQty($this->qty);


        $orderItemsExtraOption = OrderItemExtraOption::find()->where(['order_item_id' => $this->order_item_id])->all();

        if($orderItemsExtraOption) {

          foreach ($orderItemsExtraOption as $orderItemExtraOption)
             $orderItemExtraOption->delete();

        }

        return parent::beforeDelete();
    }

    public function afterDelete() {
        $order_model = Order::findOne($this->order_uuid);

        if ($order_model) {
            return $order_model->updateOrderTotalPrice();
        }

        return false;
    }

    public function beforeSave($insert) {

        parent::beforeSave($insert);
        $item_model = Item::findOne($this->item_uuid);
        $order_model = Order::findOne($this->order_uuid);

        //Update order total price
        $order_model->updateOrderTotalPrice();

        if(!$this->restaurant_uuid && $this->order) {
            $this->restaurant_uuid = $this->order->restaurant_uuid;
        }

        if ($insert) {

            if ($this->item->track_quantity && $this->qty  > $this->item->stock_qty)
                return $this->addError('qty', $this->item->item_name . " is currently out of stock and unavailable.");
        }
        else {

            if ($this->item->track_quantity && $this->qty > ( $this->item->stock_qty + $this->getOldAttribute('qty')))
                return $this->addError('qty', $this->item->item_name . " is currently out of stock and unavailable.");

        }

        if ($this->qty == 0)
            return $this->addError('qty', "Invalid input");

        //Update product inventory
        if ($insert){
          if ($item_model) {
              $this->item_name = $item_model->item_name;
              $this->item_name_ar = $item_model->item_name_ar;
          } else
              return false;


          $this->item->decreaseStockQty($this->qty);

        }

        $this->item_price = $this->calculateOrderItemPrice();

        return true;
    }

    public function afterSave($insert, $changedAttributes) {

        $item_model = Item::findOne($this->item_uuid);

        if (!$insert && isset($changedAttributes['qty'])) {

            $item_model->increaseStockQty($changedAttributes['qty']);
            $item_model->decreaseStockQty($this->qty);
        }


        $order_model = Order::findOne($this->order_uuid);

        if ($order_model)
            $order_model->updateOrderTotalPrice();

        return parent::afterSave($insert, $changedAttributes);
    }


    public function extraFields() {
        return [
            'currency',
            'orderItemExtraOptions',
            'itemImage',
            'item'
        ];
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
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImage($modelClass = "\common\models\ItemImage")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
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
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id'])
            ->via('restaurant');
    }

    /**
     * Gets query for [[OrderItemExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\common\models\OrderItemExtraOption") {
        return $this->hasMany($modelClass::className(), ['order_item_id' => 'order_item_id']);
    }

    public static function find()
    {
        return new query\OrderItemQuery(get_called_class ());
    }
}
