<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;


/**
 * This is the model class for table "refund".
 *
 * @property int $refund_id
 * @property string $restaurant_uuid
 * @property string $order_uuid
 * @property float $refund_amount

 * @property string $refund_status
 * @property string $reason
 *
 * @property Order $order
 * @property Payment $payment
 * @property Restaurant $restaurant
 * @property RefundedItem[] $refundedItems
 */
class Refund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'order_uuid', 'refund_amount'], 'required'],
            [['refund_amount'], 'number','min' => 0.1 , 'max' => $this->order->total_price,
            'tooSmall' => '{attribute} must be greater than zero.',
            'tooBig' => '{attribute} cannot exceed amount available for refund'],
            [['refund_amount'], 'validateRefundAmount'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['refund_status', 'reason'], 'string', 'max' => 255],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'refund_id',
                ],
                'value' => function () {
                    if (!$this->refund_id) {
                        $this->refund_id = 'reff_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->refund_id;
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'refund_id' => 'Refund ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'order_uuid' => 'Order Uuid',
            'refund_amount' => 'Refund amount',
            'refund_status' => 'Refund Status',
            'reason' => 'Reason for refund',

        ];
    }

    public function validateRefundAmount($attribute, $params, $validator)
    {
        if ($this->refund_amount > $this->order->total_price) {
            $this->addError($attribute, 'Can’t refund more than available');
        }
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if ($this->payment && $this->payment->payment_current_status == 'CAPTURED') {

                // Set api keys

                Yii::$app->tapPayments->setApiKeys(
                    $this->order->restaurant->live_api_key,
                    $this->order->restaurant->test_api_key
                );

                $tapPaymentResponse = Yii::$app->tapPayments->createRefund(
                    $this->payment->payment_gateway_transaction_id,
                    $this->refund_amount,
                    $this->order->currency->code,
                    $this->reason
                );

                if ($tapPaymentResponse->isOk) {
                    $this->refund_id = $tapPaymentResponse->data['id'];
                    $this->refund_status = $tapPaymentResponse->data['status'];
                } else {
                    return $this->addError('', print_r(json_encode($tapPaymentResponse->data['errors'][0]['description']), true));
                }
            }

            $order_model = Order::findOne($this->order_uuid);

            //todo: will not work for multiple refund in same order?

            if ($this->order->total_price == $this->refund_amount) {
                $order_model->order_status = Order::STATUS_REFUNDED ;
            } elseif ($this->order->total_price > $this->refund_amount) {
                $order_model->order_status = Order::STATUS_PARTIALLY_REFUNDED ;
            }

             //if($this->getRefundedItems()->count() == 0 ) {

               $order_model->subtotal_before_refund = $order_model->subtotal;
               $order_model->total_price_before_refund = $order_model->total_price;

               if($this->refund_amount > $order_model->subtotal)
                $order_model->subtotal = 0;
               else
                $order_model->subtotal -= $this->refund_amount;

               $order_model->total_price -= $this->refund_amount;
             //}

             $order_model->save(false);
        }

        return parent::beforeSave($insert);
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
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment($modelClass = "\common\models\Payment")
    {
        return $this->hasOne($modelClass::className(), ['payment_uuid' => 'payment_uuid'])
            ->via('order');
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RefundedItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItems($modelClass = "\common\models\RefundedItem")
    {
        return $this->hasMany($modelClass::className(), ['refund_id' => 'refund_id']);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem($modelClass = "\common\models\OrderItem")
    {
        return $this->hasOne($modelClass::className(), ['order_item_id' => 'order_item_id'])
            ->via('refundedItems');
    }
}
