<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "payment_failed".
 *
 * @property string $payment_failed_uuid
 * @property string $payment_uuid
 * @property string|null $order_uuid
 * @property int|null $customer_id
 * @property string|null $response
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Customer $customer
 * @property Order $order
 */
class PaymentFailed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_failed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_uuid'], 'required'],//payment_failed_uuid
            [['customer_id'], 'integer'],
            [['response'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment_failed_uuid', 'payment_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['payment_failed_uuid'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'payment_failed_uuid',
                ],
                'value' => function () {
                    if (!$this->payment_failed_uuid) {
                        $this->payment_failed_uuid = 'payment_failed_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->payment_failed_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_failed_uuid' => Yii::t('app', 'Payment Failed Uuid'),
            'payment_uuid' => Yii::t('app', 'Payment Uuid'),
            'order_uuid' => Yii::t('app', 'Order Uuid'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'response' => Yii::t('app', 'Response'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\common\models\Customer")
    {
        return $this->hasOne($modelClass::className(), ['customer_id' => 'customer_id']);
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
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via('order');
    }
}
