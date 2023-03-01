<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
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
 * @property double $plugn_fee our commision
 * @property double $partner_fee
 * @property  double $payout_status
 * @property double $payment_token
 * @property string $payment_udf1
 * @property string $payment_udf2
 * @property string $payment_udf3
 * @property string $payment_udf4
 * @property string $payment_udf5
 * @property string $payment_created_at
 * @property string $payment_updated_at
 * @property boolean $received_callback
 * @property string $payment_gateway_name
 * @property string|null $partner_payout_uuid
 * @property boolean $is_sandbox
 * @property Customer $customer
 * @property Order $order
 * @property Restaurant $restaurant
 * @property PartnerPayout $partnerPayout
 */
class Payment extends \yii\db\ActiveRecord
{
    //Values for `payout_status`
    const PAYOUT_STATUS_PENDING = 0;
    const PAYOUT_STATUS_UNPAID = 1;
    const PAYOUT_STATUS_PAID = 2;

    const SCENARIO_UPDATE_STATUS_WEBHOOK = 'webhook';

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
            [['customer_id', 'order_uuid', 'payment_amount_charged', 'restaurant_uuid', 'payment_mode'], 'required'],
            [['customer_id', 'received_callback', 'payout_status'], 'integer'],
            ['payout_status', 'in', 'range' => [self::PAYOUT_STATUS_UNPAID, self::PAYOUT_STATUS_PAID, self::PAYOUT_STATUS_PENDING]],
            [['order_uuid'], 'string', 'max' => 40],
            [['payment_gateway_order_id', 'payment_current_status'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee', 'plugn_fee', 'partner_fee'], 'number'],
            [['payment_uuid'], 'string', 'max' => 36],
            [['payment_gateway_transaction_id', 'payment_mode', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'response_message', 'payment_token', 'payment_gateway_name'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['is_sandbox'], 'boolean'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['partner_payout_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => PartnerPayout::className(), 'targetAttribute' => ['partner_payout_uuid' => 'partner_payout_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'payment_uuid',
                ],
                'value' => function () {
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
            'payment_vat' => Yii::t('app', 'VAT'),
            'payment_gateway_name' => Yii::t('app', 'Gateway name'),
            'plugn_fee' => Yii::t('app', 'Plugn Fee'),
            'payment_token' => Yii::t('app', 'Payment Token'),
            'payment_udf1' => Yii::t('app', 'Udf1'),
            'payment_udf2' => Yii::t('app', 'Udf2'),
            'payment_udf3' => Yii::t('app', 'Udf3'),
            'payment_udf4' => Yii::t('app', 'Udf4'),
            'payment_udf5' => Yii::t('app', 'Udf5'),
            'payment_created_at' => Yii::t('app', 'Created at'),
            'payment_updated_at' => Yii::t('app', 'Last activity'),
            'received_callback' => Yii::t('app', 'Received Callback'),
            'response_message' => Yii::t('app', 'Response Message'),
            'is_sandbox' => Yii::t('app', 'Is Sandbox'),
        ];
    }

    /**
     * Update Payment's Status from TAP Payments
     * @param  [type]  $id                           [description]
     * @param boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
    public static function updatePaymentStatusFromTap($id, $showUpdatedFlashNotification = false)
    {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\Payment::findOne(['payment_gateway_transaction_id' => $id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        // Request response about it from TAP
        Yii::$app->tapPayments->setApiKeys(
            $paymentRecord->restaurant->live_api_key,
            $paymentRecord->restaurant->test_api_key,
            $paymentRecord->is_sandbox
        );

        $response = Yii::$app->tapPayments->retrieveCharge($id);

        $responseContent = json_decode($response->content);

        // If there's an error from TAP, exit and display error
        if (isset($responseContent->errors)) {

            $errorMessage = "[Error from TAP]" . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description . ' - Store Name: ' . $paymentRecord->restaurant->name . ' - Order Uuid: ' . $paymentRecord->order_uuid;

            \Yii::error($errorMessage, __METHOD__); // Log error faced by user

            if($showUpdatedFlashNotification)
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
            } // Creditcard Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_VISA_MASTERCARD) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage) > Yii::$app->tapPayments->minCreditcardGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->minCreditcardGatewayFee;
            } // Mada Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_MADA) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->madaGatewayFee) > Yii::$app->tapPayments->minMadaGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->madaGatewayFee;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->madaGatewayFee;
            } // BENEFIT Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_BENEFIT) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->benefitGatewayFee) > Yii::$app->tapPayments->minBenefitGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->benefitGatewayFee;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->benefitGatewayFee;
            }

            if (isset($responseContent->destinations))
                $paymentRecord->plugn_fee = $responseContent->destinations->amount;
            else
                $paymentRecord->plugn_fee = 0;

            // Update payment method used and the order id assigned to it
            if (isset($responseContent->source->payment_method) && $responseContent->source->payment_method)
                $paymentRecord->payment_mode = $responseContent->source->payment_method;
            if (isset($responseContent->reference->payment) && $responseContent->reference->payment)
                $paymentRecord->payment_gateway_order_id = $responseContent->reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee - $paymentRecord->plugn_fee;

            \common\models\Payment::onPaymentCaptured($paymentRecord);

        } else {

            $amount = Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, $paymentRecord->currency->code, [
                \NumberFormatter::MAX_SIGNIFICANT_DIGITS => $paymentRecord->currency->decimal_place]);

            Yii::info('[TAP Payment Issue > ' . $paymentRecord->customer->customer_name . ']'
                . $paymentRecord->customer->customer_name .
                ' tried to pay ' . $amount .
                ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from TAP for Failed Payment] ' .
                print_r($responseContent, true), __METHOD__);

            //notify tech team + vendor

            self::notifyTapError($paymentRecord, $responseContent);
        }

        $paymentRecord->save();

        if ($isError) {
            throw new \Exception($errorMessage);
        }

        return $paymentRecord;
    }

    /**
     * Update Payment's Status from TAP Payments
     * @param  string  $id                         Charge id
     * @param string $status                       transaction status
     * @param string $destinations                 transaction's destination
     * @param string $source                        transaction's source
     * @param string $response_message
     */
    public static function updatePaymentStatus(
        $id,
        $status,
        $destinations = null ,
        $source = null,
        $reference,
        $response_message = null,
        $responseContent = []
    ) {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\Payment::findOne(['payment_gateway_transaction_id' => $id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        if($paymentRecord->received_callback && $paymentRecord->payment_current_status == $status )
          return $paymentRecord;

        $paymentRecord->setScenario(self::SCENARIO_UPDATE_STATUS_WEBHOOK);

        $paymentRecord->payment_current_status = $status; // 'CAPTURED' ?
        $paymentRecord->response_message = $response_message;

        // On Successful Payments
        if ($status == 'CAPTURED') {

            // KNET Gateway Fee Calculation
            if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_KNET) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->knetGatewayFee) > Yii::$app->tapPayments->minKnetGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->knetGatewayFee;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->minKnetGatewayFee;
            } // Creditcard Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_VISA_MASTERCARD) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage) > Yii::$app->tapPayments->minCreditcardGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->creditcardGatewayFeePercentage;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->minCreditcardGatewayFee;
            } // Mada Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_MADA) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->madaGatewayFee) > Yii::$app->tapPayments->minMadaGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->madaGatewayFee;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->madaGatewayFee;
            } // BENEFIT Gateway Fee Calculation
            else if ($paymentRecord->payment_mode == \common\components\TapPayments::GATEWAY_BENEFIT) {

                if (($paymentRecord->payment_amount_charged * Yii::$app->tapPayments->benefitGatewayFee) > Yii::$app->tapPayments->minBenefitGatewayFee)
                    $paymentRecord->payment_gateway_fee = $paymentRecord->payment_amount_charged * Yii::$app->tapPayments->benefitGatewayFee;
                else
                    $paymentRecord->payment_gateway_fee = Yii::$app->tapPayments->benefitGatewayFee;
            }

            if (isset($destinations))
                $paymentRecord->plugn_fee = $destinations['amount'];
            else
                $paymentRecord->plugn_fee = 0;


            // Update payment method used and the order id assigned to it
            if (isset($source->payment_method) && $source->payment_method)
                $paymentRecord->payment_mode = $source->payment_method;
            if (isset($reference->payment) && $reference->payment)
                $paymentRecord->payment_gateway_order_id = $reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee - $paymentRecord->plugn_fee;

            \common\models\Payment::onPaymentCaptured($paymentRecord);

        } else {
            Yii::info('[TAP Payment Issue: ' . $paymentRecord->customer->customer_name . ' - #' . $paymentRecord->order_uuid . ']'
                . $paymentRecord->restaurant->name . ': ' .$paymentRecord->customer->customer_name .
                ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, $paymentRecord->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $paymentRecord->currency->decimal_place]) .
                ' and has failed at gateway. '. print_r($response_message, true) . ' or maybe card issue.', __METHOD__);

            //notify tech team + vendor

            self::notifyTapError($paymentRecord, $responseContent);
        }

        return $paymentRecord;
    }

    /**
     * Update Payment's Status from Myfatoorah Payments
     * @param  [type]  $id                           [description]
     * @param boolean $showUpdatedFlashNotification [description]
     * @return self                                [description]
     */
    public static function updatePaymentStatusFromMyFatoorah($invoiceId, $showUpdatedFlashNotification = false)
    {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\Payment::find()->where(['payment_gateway_invoice_id' => $invoiceId])->one();

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        Yii::$app->myFatoorahPayment->setApiKeys($paymentRecord->currency->code);
        $response = Yii::$app->myFatoorahPayment->retrieveCharge($invoiceId, 'InvoiceId');

        $responseContent = json_decode($response->content);

        // If there's an error from MYFATOORAH, exit and display error

        if (!$responseContent->IsSuccess) {

            $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;

            \Yii::error('[Payment Issue]' . $errorMessage, __METHOD__); // Log error faced by user

            throw new NotFoundHttpException(json_encode($errorMessage));

            \Yii::$app->getSession()->setFlash('error', $errorMessage);

            return $paymentRecord;
        }

        $paymentRecord->payment_current_status = $responseContent->Data->InvoiceTransactions[0]->TransactionStatus; // 'CAPTURED' ?

        $isError = false;
        $errorMessage = "";


        // payment_gateway_fee
        $paymentRecord->payment_gateway_fee = (float)$responseContent->Data->InvoiceDisplayValue - (float)$responseContent->Data->Suppliers[0]->InvoiceShare;

        //platform fee
        if ($paymentRecord->restaurant->platform_fee > 0)
            $paymentRecord->plugn_fee = (float)$responseContent->Data->Suppliers[0]->InvoiceShare - (float)$responseContent->Data->Suppliers[0]->ProposedShare;
        else
            $paymentRecord->plugn_fee = 0;

        // Update payment method used and the order id assigned to it
        if (isset($responseContent->Data->InvoiceTransactions[0]->PaymentGateway) && $responseContent->Data->InvoiceTransactions[0]->PaymentGateway)
            $paymentRecord->payment_mode = $responseContent->Data->InvoiceTransactions[0]->PaymentGateway;
        if (isset($responseContent->reference->payment) && $responseContent->reference->payment)
            $paymentRecord->payment_gateway_order_id = $responseContent->Data->InvoiceTransactions[0]->ReferenceId;

        // Net amount after deducting gateway fee
        $paymentRecord->payment_net_amount = (float)$responseContent->Data->Suppliers[0]->DepositShare;

        // Failed Payments
        if ($responseContent->Data->InvoiceTransactions[0]->TransactionStatus != 'Success') {

            Yii::info('[MyFatoorah Payment Issue > ' . $paymentRecord->customer->customer_name . ']'
                . $paymentRecord->customer->customer_name .
                ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, $paymentRecord->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $paymentRecord->currency->decimal_place]) .
                ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from MyFatoorah for Failed Payment] ' .
                print_r($responseContent, true), __METHOD__);
        } else {

            \common\models\Payment::onPaymentCaptured($paymentRecord);

        }

        $paymentRecord->save();

        if ($isError) {
            throw new \Exception($errorMessage);
        }

        return $paymentRecord;
    }

    /**
     * Update Payment's Status from Myfatoorah Payments
     * @param  [type]  $id                           [description]
     * @param boolean $responseContent [description]
     * @return self                                [description]
     */

    public static function updatePaymentStatusFromMyFatoorahWebhook($invoiceId, $responseContent)
    {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\Payment::find()->where(['payment_gateway_invoice_id' => $invoiceId, 'received_callback' => 0])->one();

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        $paymentRecord->payment_current_status = $responseContent['TransactionStatus']; // 'SUCCESS' ?
        $paymentRecord->received_callback = 1;

        // On Successful Payments
        /*if ($responseContent['TransactionStatus'] != 'SUCCESS') {
            $paymentRecord->order->restockItems();
        }*/

        // Update payment method used and the order id assigned to it
        if ($responseContent['PaymentMethod'])
            $paymentRecord->payment_mode = $responseContent['PaymentMethod'];
        if ($responseContent['ReferenceId'])
            $paymentRecord->payment_gateway_order_id = $responseContent['ReferenceId'];

        $paymentRecord->save();

        if (
            $paymentRecord->payment_current_status == 'Paid' ||
            $paymentRecord->payment_current_status == 'Success' ||
            $paymentRecord->payment_current_status == 'SUCCESS') {
            \common\models\Payment::onPaymentCaptured($paymentRecord);
        }

        return true;
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus()
    {
        switch ($this->payout_status) {
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
     * @inheritdoc
     */
    // public function fields() {
    //     $fields = parent::fields();
    //
    //     // remove fields that contain sensitive information
    //     unset($fields['payment_net_amount']);
    //     unset($fields['payment_gateway_fee']);
    //     unset($fields['plugn_fee']);
    //
    //     return $fields;
    //
    // }

    public static function onPaymentCaptured($payment) {

        if(in_array($payment->order->order_status, [
            Order::STATUS_DRAFT,
            Order::STATUS_ABANDONED_CHECKOUT
        ])) {

            $payment->order->changeOrderStatusToPending();
            $payment->order->sendPaymentConfirmationEmail($payment);

            $invoicePaymentMethods = PaymentMethod::find()
                ->andWhere(['IN', 'payment_method_code', ["Moyasar", "Stripe"]])
                ->all();

            $invoicePaymentMethodIds = ArrayHelper::getColumn($invoicePaymentMethods, 'payment_method_id');

            if ($payment->plugn_fee > 0 && in_array($payment->order->payment_method_id, $invoicePaymentMethodIds))
            {
                $invoice = RestaurantInvoice::find()
                    ->andWhere([
                        'restaurant_uuid' => $payment->restaurant_uuid,
                        'currency_code' => $payment->order->currency_code,
                        'invoice_status' => RestaurantInvoice::STATUS_UNPAID
                    ])->one();

                if(!$invoice) {
                    $invoice = new RestaurantInvoice();
                    $invoice->restaurant_uuid = $payment->restaurant_uuid;
                    $invoice->payment_uuid = $payment->payment_uuid;
                    $invoice->amount = $payment->plugn_fee;
                    $invoice->currency_code = $payment->order->currency_code;
                }
                else {
                    $invoice->amount += $payment->plugn_fee;
                }

                if(!$invoice->save()) {
                    Yii::error(print_r($invoice->errors, true));
                }

                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_uuid = $invoice->invoice_uuid;
                $invoice_item->order_uuid = $payment->order_uuid;
                //$invoice_item->comment = $payment->order_uuid;
                $invoice_item->total = $payment->plugn_fee;

                if(!$invoice_item->save()) {
                    Yii::error(print_r($invoice->errors, true));
                }
            }

            Yii::info("[" . $payment->restaurant->name . ": " . $payment->customer->customer_name . " has placed an order for " .
                Yii::$app->formatter->asCurrency($payment->payment_amount_charged, $payment->currency->code, [
                    \NumberFormatter::MAX_SIGNIFICANT_DIGITS => $payment->currency->decimal_place]) . '] ' . 'Paid with ' .
                $payment->order->payment_method_name, __METHOD__);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->plugn_fee > 0 && $this->partner_fee == 0 && $this->restaurant->referral_code) {

            $this->partner_fee = $this->plugn_fee * $this->partner->commission;
            $this->plugn_fee -= $this->partner_fee;

            self::updateAll([
                'partner_fee' => $this->partner_fee,
                'plugn_fee' => $this->plugn_fee
            ], [
                'payment_uuid' => $this->payment_uuid
            ]);
        }
    }

    /**
     * add tap error in log
     * @param $paymentRecord
     * @param $responseContent
     * @return void
     */
    public static function notifyTapError($paymentRecord, $responseContent) {

        $model = new PaymentFailed;
        $model->payment_uuid = $paymentRecord->payment_uuid;
        $model->order_uuid = $paymentRecord->order_uuid;
        $model->customer_id = $paymentRecord->customer_id;
        $model->response = serialize($responseContent);

        if(!$model->save()) {
            Yii::error($model->errors);
        }

        /*
        //$agents = $paymentRecord->restaurant->getAgentAssignments()->all();

        //foreach ($agents as $agentAssignment) {
        
            if ($agentAssignment->email_notification) {

                \Yii::$app->mailer->compose([
                    'html' => 'payment-failed-html',
                ], [
                    'payment' => $paymentRecord,
                    'responseContent' => $responseContent
                ])
                    ->setFrom(Yii::$app->params['supportEmail'])//[$fromEmail => $this->restaurant->name]
                    //->setTo($agentAssignment->agent->agent_email)
                    ->setTo(Yii::$app->params['supportEmail'])
                    ->setSubject('Payment failed for order #' . $paymentRecord->order_uuid . ' from ' . $paymentRecord->restaurant->name)
                    //->setReplyTo($replyTo)
                    ->send();
            }
        }*/
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentFails($modelClass = "\common\models\PaymentFailed")
    {
        return $this->hasMany($modelClass::className(), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\common\models\Customer")
    {
        return $this->hasOne($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid']);
    }


    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id'])->via('order');
    }


    /**
     * Gets query for [[PartnerPayout]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerPayout()
    {
        return $this->hasOne(PartnerPayout::className(), ['partner_payout_uuid' => 'partner_payout_uuid']);
    }


    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['order_uuid' => 'order_uuid'])->via('order');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[PartnerUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['referral_code' => 'referral_code'])->via('restaurant');
    }


    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveSubscription($modelClass = "\common\models\Subscription")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->where(['subscription_status' => Subscription::STATUS_ACTIVE])->via('restaurant');
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
