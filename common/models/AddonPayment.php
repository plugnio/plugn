<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use agent\models\Currency;

/**
 * This is the model class for table "addon_payment".
 *
 * @property string $payment_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $addon_uuid
 * @property string|null $payment_gateway_order_id
 * @property string|null $payment_gateway_transaction_id
 * @property string|null $payment_mode
 * @property string|null $payment_current_status
 * @property float $payment_amount_charged
 * @property float|null $payment_net_amount
 * @property float|null $payment_gateway_fee
 * @property string|null $payment_udf1
 * @property string|null $payment_udf2
 * @property string|null $payment_udf3
 * @property string|null $payment_udf4
 * @property string|null $payment_udf5
 * @property int $received_callback
 * @property boolean $is_sandbox
 * @property string|null $response_message
 * @property string|null $payment_token
 * @property string|null $payment_created_at
 * @property string|null $payment_updated_at
 *
 * @property Addon $addon
 * @property Restaurant $restaurant
 */
class AddonPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addon_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_amount_charged'], 'required'],
            [['payment_current_status'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee'], 'number'],
            [['received_callback'], 'integer'],
            [['payment_created_at', 'payment_updated_at'], 'safe'],
            [['restaurant_uuid', 'addon_uuid'], 'string', 'max' => 60],
            [['payment_gateway_order_id', 'payment_gateway_transaction_id', 'payment_mode', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'response_message', 'payment_token'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['is_sandbox'], 'boolean'],
            [['addon_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Addon::className(), 'targetAttribute' => ['addon_uuid' => 'addon_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'payment_uuid',
                ],
                'value' => function () {
                    if (!$this->payment_uuid) {
                        $this->payment_uuid = 'payment_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

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
            'addon_uuid' => Yii::t('app', 'Addon Uuid'),
            'payment_gateway_order_id' => Yii::t('app', 'Payment Gateway Order ID'),
            'payment_gateway_transaction_id' => Yii::t('app', 'Payment Gateway Transaction ID'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'payment_current_status' => Yii::t('app', 'Payment Current Status'),
            'payment_amount_charged' => Yii::t('app', 'Payment Amount Charged'),
            'payment_net_amount' => Yii::t('app', 'Payment Net Amount'),
            'payment_gateway_fee' => Yii::t('app', 'Payment Gateway Fee'),
            'payment_udf1' => Yii::t('app', 'Payment Udf1'),
            'payment_udf2' => Yii::t('app', 'Payment Udf2'),
            'payment_udf3' => Yii::t('app', 'Payment Udf3'),
            'payment_udf4' => Yii::t('app', 'Payment Udf4'),
            'payment_udf5' => Yii::t('app', 'Payment Udf5'),
            'received_callback' => Yii::t('app', 'Received Callback'),
            'response_message' => Yii::t('app', 'Response Message'),
            'payment_token' => Yii::t('app', 'Payment Token'),
            'is_sandbox' => Yii::t('app', 'Is Sandbox?'),
            'payment_created_at' => Yii::t('app', 'Payment Created At'),
            'payment_updated_at' => Yii::t('app', 'Payment Updated At'),
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
        $paymentRecord = self::findOne(['payment_gateway_transaction_id' => $id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        // Request response about it from TAP
        Yii::$app->tapPayments->setApiKeys(
            \Yii::$app->params['liveApiKey'],
            \Yii::$app->params['testApiKey'],
            $paymentRecord->is_sandbox
        );

        $response = Yii::$app->tapPayments->retrieveCharge($id);

        $responseContent = json_decode($response->content);

        // If there's an error from TAP, exit and display error
        if (isset($responseContent->errors)) {
            $errorMessage = "[Error from TAP]" . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description  . ' - Store Name: ' . $paymentRecord->restaurant->name . ' - Addon Uuid: ' . $paymentRecord->addon_uuid;

            \Yii::error($errorMessage, __METHOD__); // Log error faced by user
            \Yii::$app->getSession()->setFlash('error', $errorMessage);
            return $paymentRecord;
        }

        $paymentRecord->payment_current_status = $responseContent->status; // 'CAPTURED' ?
        $paymentRecord->response_message = $responseContent->response->message;

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

                //Send event to Segment
                
                $kwdCurrency = Currency::findOne(['code' => 'KWD']);

                $rate = 1 / $kwdCurrency->rate;// to USD
            
                Yii::$app->eventManager->track('Addon Purchase', [
                        'addon_uuid' => $paymentRecord->addon_uuid,
                        'addon' => $paymentRecord->addon->name,
                        'paymentMethod' => $paymentRecord->payment_mode,
                        'charged' => $paymentRecord->payment_amount_charged,
                        'value' => ( $paymentRecord->payment_amount_charged * $rate),
                        'revenue' => $paymentRecord->payment_net_amount,
                        'currency' => 'USD'
                    ],
                    null, 
                    $paymentRecord->restaurant_uuid
                );


        } else {
            Yii::info('[TAP Payment Issue > ' . $paymentRecord->restaurant->name . ']'
                . $paymentRecord->restaurant->name .
                ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, 'KWD', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 3]) .
                ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from TAP for Failed Payment] ' .
                print_r($responseContent, true), __METHOD__);
        }

        if($paymentRecord->save(false) && $paymentRecord->payment_current_status == 'CAPTURED')
        {
            $model = new RestaurantAddon();
            $model->addon_uuid = $paymentRecord->addon_uuid;
            $model->restaurant_uuid = $paymentRecord->restaurant_uuid;
            $model->save();

            foreach ($paymentRecord->restaurant->getOwnerAgent()->all() as $agent ) {

                $mailter = \Yii::$app->mailer->compose([
                    'html' => 'addon-purchased',
                ], [
                    'paymentRecord' => $paymentRecord,
                    'addon' => $paymentRecord->addon,
                    'store' => $paymentRecord->restaurant,
                ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo([$agent->agent_email])
                    ->setBcc(\Yii::$app->params['supportEmail'])
                    ->setSubject('Thank you for your purchase');

                try {
                    $mailter->send();
                } catch (\Swift_TransportException $e) {
                    Yii::error($e->getMessage(), "email");
                }
            }
        }

        if ($showUpdatedFlashNotification)
            Yii::$app->session->setFlash('success', 'Updated payment status');

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
    public static function updatePaymentStatus($id, $status, $destinations = null , $source = null, $reference, $response_message = null )
    {
        // Look for payment with same Payment Gateway Transaction ID
        $paymentRecord = \common\models\SubscriptionPayment::findOne(['payment_gateway_transaction_id' => $id]);

        if (!$paymentRecord) {
            throw new NotFoundHttpException('The requested payment does not exist in our database.');
        }

        if($paymentRecord->received_callback && $paymentRecord->payment_current_status == $status )
            return $paymentRecord;

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
            }

            // Update payment method used and the order id assigned to it
            if (isset($source->payment_method) && $source->payment_method)
                $paymentRecord->payment_mode = $source->payment_method;
            if (isset($reference->payment) && $reference->payment)
                $paymentRecord->payment_gateway_order_id = $reference->payment;

            // Net amount after deducting gateway fee
            $paymentRecord->payment_net_amount = $paymentRecord->payment_amount_charged - $paymentRecord->payment_gateway_fee - $paymentRecord->plugn_fee;

        } else {
            Yii::info('[TAP Payment Issue > ' . $paymentRecord->restaurant->name . ']'
                . $paymentRecord->restaurant->name .
                ' tried to pay ' . Yii::$app->formatter->asCurrency($paymentRecord->payment_amount_charged, 'KWD', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 3]) .
                ' and has failed at gateway. Maybe card issue.', __METHOD__);

            Yii::info('[Response from TAP for Failed Payment] ' .
                print_r($response_message, true), __METHOD__);
        }

        return $paymentRecord;
    }

    /**
     * Gets query for [[AddonUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddon($modelClass = "\common\models\Addon")
    {
        return $this->hasOne(Addon::className(), ['addon_uuid' => 'addon_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Addon")
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
