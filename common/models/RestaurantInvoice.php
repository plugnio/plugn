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
 * @property string|null $order_uuid
 * @property string|null $payment_uuid
 * @property float $amount
 * @property string|null $currency_code
 * @property int|null $invoice_status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Order $orderUu
 * @property Payment $paymentUu
 * @property Restaurant $restaurantUu
 */
class RestaurantInvoice extends \yii\db\ActiveRecord
{
    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

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
            [['created_at', 'updated_at'], 'safe'],
            [['invoice_uuid', 'invoice_number', 'restaurant_uuid', 'payment_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['currency_code'], 'string', 'max' => 3],
            [['invoice_uuid'], 'unique'],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['invoice_status'] = function($model) {
            return (boolean) $model->invoice_status;
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
            'order_uuid' => Yii::t('app', 'Order Uuid'),
            'payment_uuid' => Yii::t('app', 'Payment Uuid'),
            'amount' => Yii::t('app', 'Amount'),
            'currency_code' => Yii::t('app', 'Currency Code'),
            'invoice_status' => Yii::t('app', 'Invoice Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\common\models\Order")
    {
        return $this->hasOne($modelClass::className(), ['order_uuid' => 'order_uuid']);
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
    public function getPayment($modelClass = "\common\models\Payment")
    {
        return $this->hasOne($modelClass::className(), ['payment_uuid' => 'payment_uuid']);
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
