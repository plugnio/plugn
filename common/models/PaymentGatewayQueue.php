<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment_gateway_queue".
 *
 * @property int $payment_gateway_queue_id
 * @property string $restaurant_uuid
 * @property int|null $queue_status
 * @property string|null $queue_response
 * @property string|null $payment_gateway
 * @property string|null $queue_created_at
 * @property string|null $queue_updated_at
 * @property string|null $queue_start_at
 * @property string|null $queue_end_at
 *
 * @property Restaurant $restaurant
 */
class PaymentGatewayQueue extends \yii\db\ActiveRecord
{
    //Values for `queue_status`
    const QUEUE_STATUS_PENDING = 1;
    const QUEUE_STATUS_CREATING = 2;
    const QUEUE_STATUS_COMPLETE = 3;
    const QUEUE_STATUS_FAILED = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_gateway_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'payment_gateway', 'queue_status'], 'required'],
            [['queue_status'], 'integer'],
            [['queue_start_at', 'queue_end_at'], 'safe'],
            [['queue_response'], 'string'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['payment_gateway'], 'string', 'max' => 255],
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
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'queue_created_at',
                'updatedAtAttribute' => 'queue_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function arrStatusName() {
        return [
            self::QUEUE_STATUS_PENDING => 'Pending',
            self::QUEUE_STATUS_CREATING => 'Creating',
            self::QUEUE_STATUS_COMPLETE => 'Complete',
            self::QUEUE_STATUS_FAILED => 'Failed'
        ];
    }

    public function getQueueStatusName() {
        return self::arrStatusName()[$this->queue_status];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        if(!parent::afterSave($insert, $changedAttributes)) {
            return false;
        }

        return true;
    }

    /**
     * process queue
     * @return bool|void
     */
    public function processQueue()
    {
        if(!$this->restaurant->restaurant_email)
        {
            self::updateAll([
                'queue_status' => self::QUEUE_STATUS_FAILED,
                'queue_response' => "Store email missing"
            ], [
                'payment_gateway_queue_id' => $this->payment_gateway_queue_id
            ]);

            return [
                "operation" => "error",
                "message" => "Restaurant email missing"
            ];
        }

        if ($this->payment_gateway == 'tap')
        {
            $response = $this->restaurant->createTapAccount();
        }
        else if ($this->payment_gateway == 'myfatoorah')
        {
            $response = $this->restaurant->createMyFatoorahAccount();
        }

        if ($response['operation'] == 'success')
        {
            $this->queue_status = self::QUEUE_STATUS_COMPLETE;

                //save

                self::updateAll([
                    'queue_status' => $this->queue_status
                ], [
                    'payment_gateway_queue_id' => $this->payment_gateway_queue_id
                ]);

                $paymentGateway = $this->payment_gateway == 'tap' ? 'Tap Payments' : 'MyFatoorah';
                $subject = 'Your ' . $paymentGateway . ' account has been approved';

                \Yii::$app->mailer->compose([
                    'html' => 'payment-gateway-created',
                ], [
                    'store' => $this->restaurant,
                    'paymentGateway' => $this->payment_gateway == 'tap' ? 'Tap' : 'MyFatoorah',
                ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                    ->setTo([$this->restaurant->restaurant_email])
                    ->setSubject($subject)
                    ->send();

                //enable all payment gateway by default

                if($this->payment_gateway == 'tap')
                    $this->enableTapGateways();

        }
        else
        {
            self::updateAll([
                'queue_status' => self::QUEUE_STATUS_FAILED,
                'queue_response' => json_encode($response['message'])
            ], [
                'payment_gateway_queue_id' => $this->payment_gateway_queue_id
            ]);
        }

        return $response;
    }

    /**
     * enable tap payment gateways from tap
     * @return void
     */
    public function enableTapGateways() {

        $subQuery = $this->restaurant->getRestaurantPaymentMethods()
            ->select('payment_method_id');

        $paymentGateways = PaymentMethod::find()
            ->andWhere(['LIKE', 'source_id', "src_"])
            ->andWhere(new Expression("source_id IS NOT NULL"))
            ->andWhere(['NOT IN', 'payment_method_id', $subQuery])
            ->all();

        foreach ($paymentGateways as $paymentGateway) {
            $model = new RestaurantPaymentMethod();
            $model->payment_method_id = $paymentGateway->payment_method_id;
            $model->restaurant_uuid = $this->restaurant_uuid;
            $model->status = RestaurantPaymentMethod::STATUS_ACTIVE;
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_gateway_queue_id' => Yii::t('app','Queue ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'queue_status' => Yii::t('app','Queue Status'),
            'queue_created_at' => Yii::t('app','Queue Created At'),
            'queue_updated_at' => Yii::t('app','Queue Updated At'),
            'queue_start_at' => Yii::t('app','Queue Start At'),
            'queue_end_at' => Yii::t('app','Queue End At')
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
