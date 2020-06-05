<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "refunded_item".
 *
 * @property int $refunded_item_id
 * @property string $refund_id
 * @property int $order_item_id
 * @property string $order_uuid
 * @property string|null $item_uuid
 * @property string $item_name
 * @property float $item_price
 * @property int $qty
 *
 * @property Item $item
 * @property OrderItem $orderItem
 * @property Order $order
 * @property Refund $refund
 */
class RefundedItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refunded_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refund_id', 'order_item_id', 'order_uuid', 'qty'], 'required'],
            [['order_item_id', 'qty'], 'integer'],
            ['qty', 'validateQty'],
            [['item_price'], 'number'],
            [['refund_id'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['item_uuid'], 'string', 'max' => 300],
            [['item_name'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderItem::className(), 'targetAttribute' => ['order_item_id' => 'order_item_id']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['refund_id'], 'exist', 'skipOnError' => true, 'targetClass' => Refund::className(), 'targetAttribute' => ['refund_id' => 'refund_id']],
        ];
    }


    public function validateQty($attribute, $params, $validator)
    {
        if ($this->orderItem->qty < $this->qty) {
            $this->addError($attribute, 'Invalid Qty');
        }
    }

    public function beforeSave($insert) {

        if($insert){
          $this->order_uuid = $this->order->order_uuid;
          $this->item_uuid = $this->orderItem->item_uuid;
          $this->item_name = $this->orderItem->item_name;
          $this->item_price = $this->orderItem->item_price;
        }





        return parent::beforeSave($insert);

    }

    /**
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        foreach ($this->getOrderItem()->all() as   $orderItem)
          $orderItem->delete();

        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'refunded_item_id' => 'Refunded Item ID',
          'refund_id' => 'Refund ID',
          'order_item_id' => 'Order Item ID',
          'order_uuid' => 'Order Uuid',
          'item_uuid' => 'Item Uuid',
          'item_name' => 'Item Name',
          'item_price' => 'Item Price',
          'qty' => 'Qty',
        ];
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
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * Gets query for [[OrderUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Refund]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefund()
    {
        return $this->hasOne(Refund::className(), ['refund_id' => 'refund_id']);
    }
}
