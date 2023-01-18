<?php

namespace common\models;

use agent\models\Plan;
use agent\models\Subscription;
use common\components\TapPayments;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "invoice_payment".
 *
 * @property string $payment_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $invoice_uuid
 * @property string|null $payment_gateway_transaction_id
 * @property string|null $payment_mode
 * @property string|null $payment_current_status
 * @property float $payment_amount_charged
 * @property float|null $payment_net_amount
 * @property float|null $payment_gateway_fee
 * @property int $received_callback
 * @property string|null $payment_created_at
 * @property string|null $payment_updated_at
 *
 * @property RestaurantInvoice $invoiceUu
 * @property Restaurant $restaurantUu
 */
class InvoicePayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_amount_charged'], 'required'],
            [['payment_current_status', 'currency_code'], 'string'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee'], 'number'],
            [['received_callback'], 'integer'],
            [['payment_created_at', 'payment_updated_at'], 'safe'],
            [['payment_uuid', 'restaurant_uuid', 'invoice_uuid'], 'string', 'max' => 60],
            [['payment_gateway_transaction_id', 'payment_mode'], 'string', 'max' => 255],
            [['payment_uuid'], 'unique'],
            [['invoice_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => RestaurantInvoice::className(), 'targetAttribute' => ['invoice_uuid' => 'invoice_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
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
            'invoice_uuid' => Yii::t('app', 'Invoice Uuid'),
            'payment_gateway_transaction_id' => Yii::t('app', 'Payment Gateway Transaction ID'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'payment_current_status' => Yii::t('app', 'Payment Current Status'),
            'payment_amount_charged' => Yii::t('app', 'Payment Amount Charged'),
            'payment_net_amount' => Yii::t('app', 'Payment Net Amount'),
            'payment_gateway_fee' => Yii::t('app', 'Payment Gateway Fee'),
            'currency_code' => Yii::t('app', 'Currency code'),
            'received_callback' => Yii::t('app', 'Received Callback'),
            'payment_created_at' => Yii::t('app', 'Payment Created At'),
            'payment_updated_at' => Yii::t('app', 'Payment Updated At'),
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(isset($changedAttributes['payment_current_status']) && $this->payment_current_status == 'CAPTURED') {
            $this->invoice->invoice_status = 1;
            $this->invoice->save();

            Yii::info("[" . $this->restaurant->name . ": Invoice for " .
                Yii::$app->formatter->asCurrency($this->payment_amount_charged, $this->currency_code, [
                    \NumberFormatter::MAX_SIGNIFICANT_DIGITS => $this->currency->decimal_place]) . '] has been paid', __METHOD__);
        }

        return true;
    }

    /**
     * @param $invoice_uuid
     * @param $paymentMethod
     * @return InvoicePayment|array
     */
    public static function initPayment($invoice_uuid, $payment_method_id) {

        //todo: support multi currency
        //$payment->currency_code = "KWD";
        //$payment->currency_value = 1;

        $store = Yii::$app->accountManager->getManagedAccount ();

        $invoice = RestaurantInvoice::findOne ($invoice_uuid);

        $model = new self;
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->invoice_uuid = $invoice_uuid;
        $model->payment_mode = "Moyasar";
        $model->payment_amount_charged = $invoice->amount;
        $model->currency_code = $invoice->currency_code;
        $model->payment_current_status = "Initiated";
        //$model->is_sandbox = false;//$store->is_sandbox;

        if (!$model->save ()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors ()
            ];
        }

        return $model;
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['code' => 'currency_code']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice($modelClass = "\common\models\RestaurantInvoice")
    {
        return $this->hasOne($modelClass::className(), ['invoice_uuid' => 'invoice_uuid']);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
