<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Order;
use common\models\Payment;
use common\models\OrderItem;
use common\models\Restaurant;
use yii\helpers\Html;
use yii\db\Expression;

/**
 * This is the model class for table "refund".
 *
 * @property int $refund_id
 * @property string $restaurant_uuid
 * @property string $order_uuid
 * @property string $refund_reference
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
            [['restaurant_uuid', 'order_uuid', 'refund_amount', 'payment_uuid'], 'required'],
            [['refund_amount'], 'validateRefundAmount','on' =>'create'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['payment_uuid'], 'string', 'max' => 36],
            [['refund_status', 'reason', 'refund_reference'], 'string', 'max' => 255],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
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
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'refund_created_at',
                'updatedAtAttribute' => 'refund_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }




    /**
     * Update refund's Status from Myfatoorah Payments
     * @param  [type]  $id                           [description]
     * @param  string $responseContent [description]
     * @return self                                [description]
     */
    public static function updateRefundStatus($refundReference, $responseContent) {

        // Look for refund with same refund_reference
        $refundRecord = \common\models\Refund::findOne(['refund_reference' => $refundReference]);
        if (!$refundRecord) {
            throw new yii\web\NotFoundHttpException('The requested refund does not exist in our database.');
        }

        $refundRecord->refund_status = $responseContent['RefundStatus'];

        if(!$refundRecord->save()){
           Yii::error("Error when updating refund status" . json_encode($refundRecord->errors));
        }

        return true;
    }





    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'refund_id' => Yii::t('app','Refund ID'),
            'payment_uuid' => Yii::t('app','Payment UUID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'order_uuid' => Yii::t('app','Order Uuid'),
            'refund_reference' =>Yii::t('app', 'Refund Reference'),
            'refund_amount' => Yii::t('app','Refund amount'),
            'refund_status' => Yii::t('app','Refund Status'),
            'reason' => Yii::t('app','Reason for refund')
        ];
    }

    public function validateRefundAmount($attribute, $params, $validator)
    {

      if ($this->refund_amount < 0 )
        return  $this->addError($attribute, 'Refund amount must be greater than zero.');
      else if ($this->refund_amount > $this->order->total_price)
        return  $this->addError($attribute, 'Refund amount cannot exceed amount available for refund.');

      if($this->store->is_myfatoorah_enable){

        Yii::$app->myFatoorahPayment->setApiKeys($this->currency->code);

        $totalAwaitingBalanceResponse = Yii::$app->myFatoorahPayment->getSupplierDashboard($this->store->supplierCode);

        $responseContent = json_decode($totalAwaitingBalanceResponse->content);

          if ( !$totalAwaitingBalanceResponse->isOk && !$responseContent->IsSuccess){
              $errorMessage = "Error: " . $responseContent->Message;
              Yii::error('Refund Error (#'. $this->order_uuid .'): ' . $errorMessage);
              return $this->addError($attribute, 'Refund amount cannot exceed amount available for refund.');

          }else if ($totalAwaitingBalanceResponse->isOk){
            if($responseContent->TotalAwaitingBalance < $this->refund_amount)
              return  $this->addError($attribute, 'Insufficcent Balance');


          } else {
            return $this->addError($attribute, 'Refund amount cannot exceed amount available for refund.');
          }

      }


    }

    public function beforeSave($insert)
    {
        if ($insert) {
            // if ($this->payment && $this->payment->payment_current_status == 'CAPTURED') {
            //
            //     // Set api keys
            //
            //     Yii::$app->tapPayments->setApiKeys(
            //         $this->order->restaurant->live_api_key,
            //         $this->order->restaurant->test_api_key
            //     );
            //
            //     $tapPaymentResponse = Yii::$app->tapPayments->createRefund(
            //         $this->payment->payment_gateway_transaction_id,
            //         $this->refund_amount,
            //         $this->order->currency->code,
            //         $this->reason ? $this->reason : 'requested_by_customer'
            //     );
            //
            //     if ($tapPaymentResponse->isOk) {
            //         $this->refund_id = $tapPaymentResponse->data['id'];
            //         $this->refund_status = $tapPaymentResponse->data['status'];
            //     } else {
            //         return $this->addError('', print_r(json_encode($tapPaymentResponse->data['errors'][0]['description']), true));
            //     }
            // }

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
    public function getStore($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
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
