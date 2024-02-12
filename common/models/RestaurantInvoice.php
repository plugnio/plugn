<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_invoice".
 *
 * @property string $invoice_uuid
 * @property string $invoice_number
 * @property string $restaurant_uuid
 * @property string|null $payment_uuid
 * @property float $amount
 * @property string|null $currency_code
 * @property boolean $mail_sent
 * @property int|null $invoice_status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Order $order
 * @property Payment $payment
 * @property Restaurant $restaurant
 */
class RestaurantInvoice extends \yii\db\ActiveRecord
{
    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;
    const STATUS_LOCKED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'amount'], 'required'],//'invoice_uuid', 'invoice_number',
            [['amount'], 'number'],
            [['invoice_status'], 'integer'],
            [['mail_sent'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['invoice_uuid', 'invoice_number', 'restaurant_uuid', 'payment_uuid'], 'string', 'max' => 60],
            [['currency_code'], 'string', 'max' => 3],
            [['invoice_uuid'], 'unique'],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    public function extraFields()
    {
        return array_merge([
            'invoiceItems',
            'paymentMethods'
        ], parent::extraFields());
    }

    /**
     * payment methods available for this invoice to pay invoice amount
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPaymentMethods() {

        return PaymentMethod::find ()
            ->joinWith('paymentMethodCurrencies')
            ->andWhere([
                'OR',
                ['payment_method_currency.currency' => $this->currency_code],
                [
                    'IN',
                    'payment_method_code',
                    [
                        PaymentMethod::CODE_MOYASAR,
                        PaymentMethod::CODE_STRIPE
                    ]
                ]
            ])
            ->all();
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['invoice_status'] = function($model) {
            return (int) $model->invoice_status;
        };

        return $fields;
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'invoice_uuid',
                ],
                'value' => function () {
                    if (!$this->invoice_uuid) {
                        // Get a unique uuid
                        $this->invoice_uuid = 'invoice_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->invoice_uuid;
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'invoice_number',
                ],
                'value' => function () {
                    if (!$this->invoice_number) {
                        // Get a unique uuid
                        $this->invoice_number = strtoupper(self::getUniqueInvoiceNumber());
                    }

                    return $this->invoice_number;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * Get a unique alphanumeric uuid
     * @return string uuid
     */
    private static function getUniqueInvoiceNumber($length = 6)
    {
        $uuid = \ShortCode\Random::get($length);

        $isNotUnique = static::find()->where(['invoice_number' => $uuid])->exists();

        // If not unique, try again recursively
        if ($isNotUnique) {
            return static::getUniqueInvoiceNumber($length);
        }

        return $uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invoice_uuid' => Yii::t('app', 'Invoice Uuid'),
            'invoice_number' => Yii::t('app', 'Invoice Number'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'payment_uuid' => Yii::t('app', 'Payment Uuid'),
            'amount' => Yii::t('app', 'Amount'),
            'currency_code' => Yii::t('app', 'Currency Code'),
            'mail_sent' => Yii::t('app', 'Mail Sent'),
            'invoice_status' => Yii::t('app', 'Invoice Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * if mail not sent, mark as lock and notify
     */
    public function sendEmail() {

        $this->invoice_status = self::STATUS_LOCKED;
        $this->mail_sent = true;
        $this->save();

        $agentAssignments = $this->restaurant->getAgentAssignments()
            ->andWhere(['email_notification' => true])
            ->all();

        $emails = [];

        foreach ($agentAssignments as $agentAssignment)
            $emails[] = $agentAssignment->agent->agent_email;

        if ($this->restaurant->restaurant_email_notification && $this->restaurant->restaurant_email)
            $emails[] = $this->restaurant->restaurant_email;

        if(sizeof($emails) ==  0)
            return true;

        $mailer = \Yii::$app->mailer->compose([
            'html' => 'store/pending-invoice-html',
        ], [
            'invoice' => $this
        ])
            ->setFrom(\Yii::$app->params['noReplyEmail'])//[$fromEmail => $this->restaurant->name]
            ->setTo($emails[0])
            ->setCc(array_slice($emails, 1))
            ->setSubject('Invoice #' . $this->invoice_number . ' for Plugn commission | ' . $this->restaurant->name)
            ->setReplyTo(\Yii::$app->params['supportEmail']);

        try {
            $mailer->send();
        } catch (\Swift_TransportException $e) {
            Yii::error($e->getMessage(), "email");
        }
    }

    public function getRestaurantName() {
        if($this->restaurant)
            return $this->restaurant->name;
    }

    /**
     * Gets query for [[InvoiceItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceItems($modelClass = "\common\models\InvoiceItem")
    {
        return $this->hasMany($modelClass::className(), ['invoice_uuid' => 'invoice_uuid']);
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
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment($modelClass = "\common\models\InvoicePayment")
    {
        return $this->hasOne($modelClass::className(), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments($modelClass = "\common\models\InvoicePayment")
    {
        return $this->hasMany($modelClass::className(), ['invoice_uuid' => 'invoice_uuid'])
            ->orderBy('payment_created_at ASC');
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

    /**
     * @return query\RestaurantInvoiceQuery
     */
    public static function find() {
        return new query\RestaurantInvoiceQuery(get_called_class());
    }
}
