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
 * @property string $subscription_uuid Which order this payment is for
 * @property string $payment_gateway_order_id myfatoorah order id
 * @property string $payment_gateway_transaction_id myfatoorah transaction id
 * @property string $payment_mode which gateway they used
 * @property string $payment_current_status Where are we with this payment / result
 * @property double $payment_amount_charged amount charged to customer
 * @property double $payment_net_amount net amount deposited into our account
 * @property double $payment_gateway_fee gateway fee charged
 * @property double $payment_token
 * @property string $payment_udf1
 * @property string $payment_udf2
 * @property string $payment_udf3
 * @property string $payment_udf4
 * @property string $payment_udf5
 * @property string $payment_created_at
 * @property string $payment_updated_at
 * @property boolean $received_callback
 * @property double $partner_fee
 * @property  double $payout_status
 * @property string|null $partner_payout_uuid
 *
 * @property Subscription $subscription
 * @property Plan $plan
 * @property Restaurant $restaurant
 * @property PartnerPayout $partnerPayout
 */
class SubscriptionPayment extends \yii\db\ActiveRecord {


    //Values for `payout_status`
    const PAYOUT_STATUS_PENDING = 0;
    const PAYOUT_STATUS_UNPAID = 1;
    const PAYOUT_STATUS_PAID = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'subscription_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['subscription_uuid', 'payment_amount_charged', 'restaurant_uuid', 'payment_mode'], 'required'],
            [['received_callback','payout_status'], 'integer'],
            ['payout_status', 'in', 'range' => [self::PAYOUT_STATUS_UNPAID, self::PAYOUT_STATUS_PAID,self::PAYOUT_STATUS_PENDING]],
            [['payment_gateway_order_id', 'payment_current_status'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee','partner_fee'], 'number'],
            [['payment_uuid'], 'string', 'max' => 36],
            [['payment_gateway_transaction_id', 'payment_mode', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'response_message', 'payment_token'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['subscription_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::className(), 'targetAttribute' => ['subscription_uuid' => 'subscription_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['partner_payout_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => PartnerPayout::className(), 'targetAttribute' => ['partner_payout_uuid' => 'partner_payout_uuid']]
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
     * Returns String value of current status
     * @return string
     */
    public function getStatus(){
        switch($this->payout_status){
            case self::PAYOUT_STATUS_UNPAID:
                return "Unpaid";
                break;
            case self::PAYOUT_STATUS_PENDING:
                return "Pending";
                break;
            case self::PAYOUT_STATUS_PAID:
                return "Paid";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'payment_uuid' => Yii::t('app', 'Payment Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'payment_gateway_order_id' => Yii::t('app', 'Gateway Order ID'),
            'payment_gateway_transaction_id' => Yii::t('app', 'Gateway Transaction ID'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'payment_current_status' => Yii::t('app', 'Current Status'),
            'payment_amount_charged' => Yii::t('app', 'Amount Charged'),
            'payment_net_amount' => Yii::t('app', 'Net Amount'),
            'payment_gateway_fee' => Yii::t('app', 'Gateway Fee'),
            'payment_token' => Yii::t('app', 'Payment Token'),
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
            'partner_fee' => Yii::t('app', 'Plugn fee'),
            'payout_status' => Yii::t('app', 'Payout status'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['partner_fee']);
        unset($fields['payout_status']);

        return $fields;
    }

    /**
     * Update Payment's Status from TAP Payments
     * @param  [type]  $id                           [description]
     * @param  boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
    public static function updatePaymentStatusFromTap($id, $showUpdatedFlashNotification = false) {

        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\SubscriptionPayment::findOne(['payment_gateway_transaction_id' => $id]);
        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        // Request response about it from TAP
        Yii::$app->tapPayments->setApiKeys(\Yii::$app->params['liveApiKey'], \Yii::$app->params['testApiKey']);

        $response = Yii::$app->tapPayments->retrieveCharge($id);

        $responseContent = json_decode($response->content);

        // If there's an error from TAP, exit and display error
        if (isset($responseContent->errors)) {
            $errorMessage = "[Error from TAP]" . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description  . ' - Store Name: ' . $paymentRecord->restaurant->name . ' - Order Uuid: ' . $paymentRecord->order_uuid;

            \Yii::error($errorMessage, __METHOD__); // Log error faced by user
            \Yii::$app->getSession()->setFlash('error', $errorMessage);
            return $paymentRecord;
        }

        $paymentRecord->payment_current_status = $responseContent->status; // 'CAPTURED' ?
        $paymentRecord->response_message = $responseContent->response->message;

        $isError = false;
        $errorMessage = "";

        // On Successful Payments
        if ($responseContent->status == 'CAPTURED') {


          // KNET Gateway Fee Calculation
          if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_KNET) {

              if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->knetGatewayFee) > Yii::$app->tapPayments->minKnetGatewayFee)
                  $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->knetGatewayFee;
              else
                  $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->minKnetGatewayFee;
          }

          // Creditcard Gateway Fee Calculation
          if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_VISA_MASTERCARD) {

              if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage) > Yii::$app->tapPayments->minCreditcardGatewayFee)
                  $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage;
              else
                  $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->minCreditcardGatewayFee;
          }


            // Update payment method used and the order id assigned to it
            if (isset($responseContent->source->payment_method) && $responseContent->source->payment_method)
                $paymentRecord->payment_mode = $responseContent->source->payment_method;
            if (isset($responseContent->reference->payment) && $responseContent->reference->payment)
                $paymentRecord->payment_gateway_order_id = $responseContent->reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee;

        if(YII_ENV == 'prod') {
            //Send event to Segment
            \Segment::init('2b6WC3d2RevgNFJr9DGumGH5lDRhFOv5');
            \Segment::track([
                  'userId' => $paymentRecord->restaurant_uuid,
                  'event' => 'Premium Plan Purchase',
                  'properties' => [
                      'order_id' => $paymentRecord->payment_uuid,
                      'value' => ( $paymentRecord->payment_amount_charged * 3.28 ),
                      'paymentMethod' => $paymentRecord->payment_mode,
                      'currency' => 'USD'
                  ]
              ]);
          }


        } else {
            Yii::info('[TAP Payment Issue > ' . $paymentRecord->restaurant->name . ']'
                    . $paymentRecord->restaurant->name .
                    ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, $paymentRecord->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $paymentRecord->currency->decimal_place]) .
                    ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from TAP for Failed Payment] ' .
                    print_r($responseContent, true), __METHOD__);
        }

        if($paymentRecord->save() && $paymentRecord->payment_current_status == 'CAPTURED' ){
            Subscription::updateAll(['subscription_status' => Subscription::STATUS_INACTIVE], ['and', ['subscription_status' => Subscription::STATUS_ACTIVE], ['restaurant_uuid' => $paymentRecord->restaurant_uuid]]);
            $subscription_model = $paymentRecord->subscription;
            $subscription_model->subscription_status = Subscription::STATUS_ACTIVE;

            $valid_for =  $subscription_model->plan->valid_for;

            $subscription_model->subscription_end_at = date('Y-m-d', strtotime(date('Y-m-d H:i:s',  strtotime($subscription_model->subscription_start_at)) . " + $valid_for MONTHS"));

            $subscription_model->save(false);

            foreach ($subscription_model->restaurant->getOwnerAgent()->all() as $agent ) {

              \Yii::$app->mailer->compose([
                     'html' => 'premium-upgrade',
                         ], [
                     'subscription' => $subscription_model,
                     'store' => $paymentRecord->restaurant,
                 ])
                 ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                 ->setTo([$agent->agent_email])
                 ->setBcc(\Yii::$app->params['supportEmail'])
                 ->setSubject('Your store '. $paymentRecord->restaurant->name . ' has been upgraded to our '. $subscription_model->plan->name)
                 ->send();
            }
        }

        if ($isError) {
            throw new \Exception($errorMessage);
        }

        if ($showUpdatedFlashNotification)
            Yii::$app->session->setFlash('success', 'Updated payment status');

        return $paymentRecord;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription($modelClass = "\common\models\Subscription") {
        return $this->hasOne($modelClass::className(), ['subscription_uuid' => 'subscription_uuid']);
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan($modelClass = "\common\models\Plan") {
        return $this->hasOne($modelClass::className(), ['plan_id' => 'plan_id'])->via('subscription');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }


    /**
     * Gets query for [[PartnerPayout]].
     *
     * @return \yii\db\ActiveQuery
     */
     public function getPartnerPayout(){
           return $this->hasOne(PartnerPayout::className(), ['partner_payout_uuid' => 'partner_payout_uuid']);
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

}
