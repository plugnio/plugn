<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $order_item_id
 * @property int $order_id
 * @property string $item_uuid
 * @property string $item_name
 * @property float $item_price
 * @property int|null $qty
 * @property string|null $instructions
 *
 * @property Item $item
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
            [['order_id', 'item_uuid'], 'required'],
            [['order_id', 'qty'], 'integer'],
            [['item_price'], 'number'],
            [['item_uuid'], 'string', 'max' => 300],
            [['item_name', 'instructions'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_item_id' => 'Order Item ID',
            'order_id' => 'Order ID',
            'item_uuid' => 'Item Uuid',
            'item_name' => 'Item Name',
            'item_price' => 'Item Price',
            'qty' => 'Qty',
            'instructions' => 'Instructions',
        ];
    }

    /**
     * Calculate order item total price => (item price + extra optns price)
     */
    public function calculateOrderItemPrice() {
        $totalPrice = $this->item_price; //5

        foreach ($this->getOrderItemExtraOptions()->asArray()->all() as $extraOption)
            $totalPrice += $extraOption['extra_option_price']; //1

        $totalPrice *= $this->qty; //6*5

        return $totalPrice;
    }

    public function beforeSave($insert) {
        parent::beforeSave($insert);

        if ($this->qty > $this->item->stock_qty) {
            return $this->addError('qty', "The requested quantity for " . $this->item->item_name . " is not available.: ");
        }

        $item_model = Item::findOne($this->item_uuid);
        
        if ($item_model) {
            $this->item_name = $item_model->item_name;
            $this->item_price = $item_model->item_price;
            
            //update stock_qty
            $item_model->stock_qty -= $this->qty;
            $item_model->save(false);
        } else
            return false;


        return true;
    }
  

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem() {
        return $this->hasOne(Item::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * Gets query for [[OrderItemExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions() {
        return $this->hasMany(OrderItemExtraOption::className(), ['order_item_id' => 'order_item_id']);
    }

}
