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
 *
 * @property Subscription $subscription
 * @property Plan $plan
 * @property Restaurant $restaurant
 */
class SubscriptionPayment extends \yii\db\ActiveRecord {

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
            [['subscription_uuid', 'payment_amount_charged', 'restaurant_uuid'], 'required'],
            [['received_callback'], 'integer'],
            [['payment_gateway_order_id', 'payment_current_status'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee'], 'number'],
            [['payment_uuid'], 'string', 'max' => 36],
            [['payment_gateway_transaction_id', 'payment_mode', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'response_message', 'payment_token'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['subscription_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::className(), 'targetAttribute' => ['subscription_uuid' => 'subscription_uuid']],
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
        ];
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
            $errorMessage = "Error from TAP: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;
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

            // Update payment method used and the order id assigned to it
            if (isset($responseContent->source->payment_method) && $responseContent->source->payment_method)
                $paymentRecord->payment_mode = $responseContent->source->payment_method;
            if (isset($responseContent->reference->payment) && $responseContent->reference->payment)
                $paymentRecord->payment_gateway_order_id = $responseContent->reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee;
        } else {
            Yii::info('[TAP Payment Issue > ' . $paymentRecord->restaurant->name . ']'
                    . $paymentRecord->restaurant->name .
                    ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, '', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10]) .
                    ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from TAP for Failed Payment] ' .
                    print_r($responseContent, true), __METHOD__);
        }

        if($paymentRecord->save() && $paymentRecord->payment_current_status == 'CAPTURED' ){
            Subscription::updateAll(['subscription_status' => Subscription::STATUS_INACTIVE], ['and', ['subscription_status' => Subscription::STATUS_ACTIVE], ['restaurant_uuid' => $paymentRecord->restaurant_uuid]]);
            $subscription_model = $paymentRecord->subscription;
            $subscription_model->subscription_status = Subscription::STATUS_ACTIVE;
            $subscription_model->save(false);
        }

        if ($isError) {
            throw new \Exception($errorMessage);
        }

        if ($showUpdatedFlashNotification)
            Yii::$app->session->setFlash('success', 'Updated payment status');

        return $paymentRecord;
    }


    public function beforeSave($insert) {

        //TODO
        if(!$insert && $this->payment_current_status == 'CAPTURED'){

            //send to all store's owner

            foreach ($this->restaurant->getOwnerAgent()->all() as $agent) {

                \Yii::$app->mailer->compose([
                       'html' => 'plan-upgraded-html',
                           ], [
                       'subscription' => $this->subscription,
                       'store' => $this->restaurant,
                   ])
                   ->setFrom([\Yii::$app->params['supportEmail']])
                   ->setTo($agent->agent_email)
                   ->setSubject('Your store was successfully upgraded')
                   ->send();

            }


        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription() {
        return $this->hasOne(Subscription::className(), ['subscription_uuid' => 'subscription_uuid']);
    }


    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan() {
        return $this->hasOne(Plan::className(), ['plan_id' => 'plan_id'])->via('subscription');
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
