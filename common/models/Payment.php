<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "payment".
 *
 * @property string $payment_uuid used as payment reference id
 * @property string $restaurant_uuid
 * @property string $customer_id Which customer made the payment?
 * @property string $order_uuid Which order this payment is for
 * @property string $payment_gateway_order_id myfatoorah order id
 * @property string $payment_gateway_transaction_id myfatoorah transaction id
 * @property string $payment_mode which gateway they used
 * @property string $payment_current_status Where are we with this payment / result
 * @property double $payment_amount_charged amount charged to customer
 * @property double $payment_net_amount net amount deposited into our account
 * @property double $payment_gateway_fee gateway fee charged
 * @property string $payment_udf1
 * @property string $payment_udf2
 * @property string $payment_udf3
 * @property string $payment_udf4
 * @property string $payment_udf5
 * @property string $payment_created_at
 * @property string $payment_updated_at
 * @property boolean $received_callback
 *
 * @property Customer $customer
 * @property Order $order
 * @property Restaurant $restaurant
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_uuid', 'payment_amount_charged', 'restaurant_uuid'], 'required'],
            [['customer_id','received_callback'], 'integer'],
            [['order_uuid'], 'string', 'max' => 40],
            [['payment_gateway_order_id', 'payment_current_status'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee'], 'number'],
            [['payment_uuid'], 'string', 'max' => 36],
            [['payment_gateway_transaction_id', 'payment_mode', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5','response_message'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'payment_uuid',
                ],
                'value' => function() {
                    if (!$this->payment_uuid)
                        $this->payment_uuid = Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->payment_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'payment_created_at',
                'updatedAtAttribute' => 'payment_updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_uuid' => Yii::t('app', 'Payment Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'payment_gateway_order_id' => Yii::t('app', 'Gateway Order ID'),
            'payment_gateway_transaction_id' => Yii::t('app', 'Gateway Transaction ID'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'payment_current_status' => Yii::t('app', 'Current Status'),
            'payment_amount_charged' => Yii::t('app', 'Amount Charged'),
            'payment_net_amount' => Yii::t('app', 'Net Amount'),
            'payment_gateway_fee' => Yii::t('app', 'Gateway Fee'),
            'payment_udf1' => Yii::t('app', 'Udf1'),
            'payment_udf2' => Yii::t('app', 'Udf2'),
            'payment_udf3' => Yii::t('app', 'Udf3'),
            'payment_udf4' => Yii::t('app', 'Udf4'),
            'payment_udf5' => Yii::t('app', 'Udf5'),
            'payment_created_at' => Yii::t('app', 'Created at'),
            'payment_updated_at' => Yii::t('app', 'Last activity'),
            'payment_updated_at' => Yii::t('app', 'Last activity'),
            'received_callback' => Yii::t('app', 'Received Callback'),
            'response_message' => Yii::t('app', 'Response Message'),
        ];
    }


    /**
     * Update Payment's Status from TAP Payments
     * @param  [type]  $id                           [description]
     * @param  boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
    public static function updatePaymentStatusFromTap($id, $showUpdatedFlashNotification = false)
    {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\Payment::findOne(['payment_gateway_transaction_id' => $id]);
        if(!$paymentRecord){
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }


        // Request response about it from TAP
        Yii::$app->tapPayments->setApiKeys($paymentRecord->restaurant->live_api_key, $paymentRecord->restaurant->test_api_key);

        $response = Yii::$app->tapPayments->retrieveCharge($id);
        $responseContent = json_decode($response->content);

        // If there's an error from TAP, exit and display error
        if(isset($responseContent->errors)){
            $errorMessage = "Error from TAP: ".$responseContent->errors[0]->code. " - ". $responseContent->errors[0]->description;
            \Yii::error($errorMessage, __METHOD__); // Log error faced by user
            \Yii::$app->getSession()->setFlash('error', $errorMessage);
            return $paymentRecord;
        }

        $paymentRecord->payment_current_status = $responseContent->status; // 'CAPTURED' ?
        $paymentRecord->response_message = $responseContent->response->message;

        $isError = false;
        $errorMessage = "";

        // On Successful Payments
        if($responseContent->status == 'CAPTURED'){
            // Check if Fee object exists then use the fee set from there.
            if(isset($responseContent->fees) && isset($responseContent->fees->amount)){
                $paymentRecord->payment_gateway_fee = $responseContent->fees->amount;
            }else{// Otherwise do your own fee calculation
                // KNET Gateway Fee Calculation
                if($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_KNET){
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->knetGatewayFee;
                }
                // Creditcard Gateway Fee Calculation
                if($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_VISA_MASTERCARD){
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage;
                }
            }

            // Update payment method used and the order id assigned to it
            $paymentRecord->payment_mode = $responseContent->source->payment_method;
            $paymentRecord->payment_gateway_order_id = $responseContent->reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee;

        }

        $paymentRecord->save();

        if($isError){
            throw new \Exception($errorMessage);
        }

        if($showUpdatedFlashNotification)
            Yii::$app->session->setFlash('success', 'Updated payment status');

        return $paymentRecord;
    }



    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if($this->payment_current_status == 'CAPTURED')
            $this->order->sendPaymentConfirmationEmail();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
