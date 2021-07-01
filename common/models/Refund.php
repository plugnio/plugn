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
            'refund_id' => 'Refund ID',
            'payment_uuid' => 'Payment UUID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'order_uuid' => 'Order Uuid',
            'refund_reference' => 'Refund Reference',
            'refund_amount' => 'Refund amount',
            'refund_status' => 'Refund Status',
            'reason' => 'Reason for refund',

        ];
    }


    public function validateRefundAmount($attribute, $params, $validator)
    {

      Yii::error('enter here', __METHOD__);
      if ($this->refund_amount < 0 )
        return  $this->addError($attribute, 'Refund amount must be greater than zero.');
      else if ($this->refund_amount > $this->order->total_price)
        return  $this->addError($attribute, 'Refund amount cannot exceed amount available for refund.');

      if($this->store->is_myfatoorah_enable){
        Yii::error('enter is_myfatoorah_enable', __METHOD__);

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

      } else {
        Yii::error('enter is_tap_enable', __METHOD__);
      }


    }




    public function beforeSave($insert)
    {
        if ($insert) {

            $order_model = Order::findOne($this->order_uuid);

            if ($this->order->total_price == $this->refund_amount) {
                $order_model->order_status = Order::STATUS_REFUNDED ;
            } elseif ($this->order->total_price > $this->refund_amount) {
                $order_model->order_status = Order::STATUS_PARTIALLY_REFUNDED ;
            }


             if($this->getRefundedItems()->count() == 0 ) {
               $order_model->subtotal_before_refund = $order_model->subtotal;
               $order_model->total_price_before_refund = $order_model->total_price;

               if($this->refund_amount > $order_model->subtotal)
                $order_model->subtotal = 0;
               else
                $order_model->subtotal -= $this->refund_amount;

               $order_model->total_price -= $this->refund_amount;
             }



             $order_model->save(false);

        }

        return parent::beforeSave($insert);
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
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
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
    public function getRefundedItems()
    {
        return $this->hasMany(RefundedItem::className(), ['refund_id' => 'refund_id']);
    }


    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['order_item_id' => 'order_item_id'])->via('refundedItems');
    }
}
