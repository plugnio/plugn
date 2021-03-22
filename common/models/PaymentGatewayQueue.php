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
    public function rules() {
        return [
            [['restaurant_uuid', 'queue_status'], 'required'],
            [['queue_status'], 'integer'],
            [['queue_start_at', 'queue_end_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['payment_gateway'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }



    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'queue_created_at',
                'updatedAtAttribute' => 'queue_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }




      public function afterSave($insert, $changedAttributes) {

        if ($this->queue_status == self::QUEUE_STATUS_CREATING) {
          if ($this->payment_gateway == 'tap')
            $response =  $this->restaurant->createAnAccountOnTap();
          else if ($this->payment_gateway == 'myfatoorah'){
            $response =  $this->restaurant->createAnAccountOnMyFatoorah();
          }

              if($response){
                $this->queue_status = self::QUEUE_STATUS_COMPLETE;
                if($this->save()){
                  \Yii::$app->mailer->compose([
                         'html' => 'payment-gateway-created',
                             ], [
                         'store' => $this->restaurant,
                         'paymentGateway' => $this->payment_gateway == 'tap' ? 'Tap' : 'My Fatoorah',
                     ])
                     ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                     ->setTo([$this->restaurant->restaurant_email])
                     ->setBcc(\Yii::$app->params['supportEmail'])
                     ->setSubject('Your '. $this->payment_gateway == 'tap' ? 'Tap' : 'My Fatoorah' .' Payments account has been approved')
                     ->send();
                }
              }

          }



        return parent::afterSave($insert, $changedAttributes);

      }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_gateway_queue_id' => 'Queue ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'queue_status' => 'Queue Status',
            'queue_created_at' => 'Queue Created At',
            'queue_updated_at' => 'Queue Updated At',
            'queue_start_at' => 'Queue Start At',
            'queue_end_at' => 'Queue End At',
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
