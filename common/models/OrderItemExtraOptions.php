<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item_extra_options".
 *
 * @property int $order_item_extra_options_id
 * @property int $order_item_id
 * @property int $extra_option_id
 * @property string $extra_option_name
 * @property string $extra_option_name_ar
 * @property float $extra_option_price
 *
 * @property ExtraOption $extraOption
 * @property OrderItem $orderItem
 */
class OrderItemExtraOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item_extra_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_item_id', 'extra_option_id', 'extra_option_name', 'extra_option_name_ar', 'extra_option_price'], 'required'],
            [['order_item_id', 'extra_option_id'], 'integer'],
            [['extra_option_price'], 'number'],
            [['extra_option_name', 'extra_option_name_ar'], 'string', 'max' => 255],
            [['extra_option_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExtraOption::className(), 'targetAttribute' => ['extra_option_id' => 'extra_option_id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderItem::className(), 'targetAttribute' => ['order_item_id' => 'order_item_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_item_extra_options_id' => 'Order Item Extra Options ID',
            'order_item_id' => 'Order Item ID',
            'extra_option_id' => 'Extra Option ID',
            'extra_option_name' => 'Extra Option Name',
            'extra_option_name_ar' => 'Extra Option Name Ar',
            'extra_option_price' => 'Extra Option Price',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id'])->via('orderItem');
    }
    
    /**
     * Gets query for [[ExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOption()
    {
        return $this->hasOne(ExtraOption::className(), ['extra_option_id' => 'extra_option_id'])->inverseOf('orderItemExtraOptions');
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['order_item_id' => 'order_item_id'])->inverseOf('orderItemExtraOptions');
    }
}
