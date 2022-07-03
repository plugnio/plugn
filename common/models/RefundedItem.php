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
 * @property string $item_name_ar
 * @property float $item_price
 * @property int $qty
 *
 * @property Item $item
 * @property OrderItem $orderItem
 * @property Order $order
 * @property Restaurant $restaurant
 * @property Refund $refund
 * @property ItemImage[] $itemImages
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
            [['refund_id', 'order_item_id', 'order_uuid', 'item_uuid', 'item_name', 'item_price', 'qty'], 'required'],
            [['order_item_id', 'qty'], 'integer'],
            ['qty', 'validateQty'],
            [['item_price'], 'number'],
            [['refund_id'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['item_uuid'], 'string', 'max' => 300],
            [['item_name', 'item_name_ar'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderItem::className(), 'targetAttribute' => ['order_item_id' => 'order_item_id']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['refund_id'], 'exist', 'skipOnError' => true, 'targetClass' => Refund::className(), 'targetAttribute' => ['refund_id' => 'refund_id']],
        ];
    }


    public function validateQty($attribute, $params, $validator)
    {
        if ($this->orderItem->qty < $this->qty)
            $this->addError($attribute, Yii::t('app','Invalid Qty'));

    }

    /*public function beforeSave($insert) {

        if($insert) {

          $this->order_uuid = $this->order->order_uuid;
          $this->item_uuid = $this->orderItem->item_uuid;
          $this->item_name = $this->orderItem->item_name;
          $this->item_name_ar = $this->orderItem->item_name_ar;
          $this->item_price = $this->orderItem->item_price;
        }

        return parent::beforeSave($insert);
    }*/

    /**
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        $order_item_model = $this->orderItem;

        if($this->qty == $order_item_model->qty)
          $order_item_model->delete();
        else {
          $order_item_model->qty -= $this->qty;
          $order_item_model->save(false);
        }
        // foreach ($this->getOrderItem()->all() as   $orderItem)
        //   $orderItem->delete();

        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'refunded_item_id' => Yii::t('app','Refunded Item ID'),
          'refund_id' => Yii::t('app','Refund ID'),
          'order_item_id' => Yii::t('app','Order Item ID'),
          'order_uuid' => Yii::t('app','Order Uuid'),
          'item_uuid' => Yii::t('app','Item Uuid'),
          'item_name' => Yii::t('app','Item Name'),
          'item_name_ar' => Yii::t('app','Item Name - Arabic'),
          'item_price' => Yii::t('app','Item Price'),
          'qty' => Yii::t('app','Qty')
        ];
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStore($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->via('order');
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem($modelClass = "\common\models\OrderItem")
    {
        return $this->hasOne($modelClass::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages($modelClass = "\common\models\ItemImage")
    {
        return $this->hasMany($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }

    /**
     * Gets query for [[OrderUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id'])->via('store');
    }


    /**
     * Gets query for [[Refund]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefund($modelClass = "\common\models\Refund")
    {
        return $this->hasOne($modelClass::className(), ['refund_id' => 'refund_id']);
    }
}
