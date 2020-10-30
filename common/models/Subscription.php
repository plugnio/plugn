<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property int $subscription_uuid
 * @property string|null $restaurant_uuid
 * @property int $plan_id
 * @property int $payment_uuid
 * @property int $subscription_status
 * @property int $notified_email
 * @property string|null $subscription_start_at
 * @property string|null $subscription_end_at
 *
 * @property SubscriptionPayment $subscriptionPayment
 * @property Plan $plan
 * @property Restaurant $restaurant
 */
class Subscription extends \yii\db\ActiveRecord {

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['plan_id', 'restaurant_uuid'], 'required'],
            [['plan_id', 'subscription_status', 'notified_email'], 'integer'],
            [['subscription_start_at', 'subscription_end_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['payment_uuid'], 'string', 'max' => 36],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => SubscriptionPayment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'subscription_uuid',
                ],
                'value' => function() {
                    if (!$this->subscription_uuid)
                        $this->subscription_uuid = 'sub_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->subscription_uuid;
                }
            ],
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'subscription_start_at',
                'updatedAtAttribute' => false,
                'value' => function() {
                  //TODO
                    if (!$this->subscription_start_at)
                        $this->subscription_start_at = new \yii\db\Expression('NOW()');

                    return $this->subscription_start_at;
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'subscription_uuid' => 'Subscription Uuid',
            'payment_uuid' => 'Payment Uuid',
            'restaurant_uuid' => 'Restaurant Uuid',
            'plan_id' => 'Plan ID',
            'subscription_start_at' => 'Subscription Start At',
            'subscription_end_at' => 'Subscription End At',
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus(){
        switch($this->subscription_status){
            case self::STATUS_ACTIVE:
                return "Active";
                break;
            case self::STATUS_INACTIVE:
                return "Inactive";
                break;
        }
    }


    public function beforeSave($insert) {

        $vaid_for = $this->plan->valid_for;
        if ($vaid_for && !$this->subscription_end_at)
            $this->subscription_end_at = date('Y-m-d', strtotime(date('Y-m-d',  strtotime($this->subscription_start_at)) . " + $vaid_for MONTHS"));

          if($this->payment_uuid && $this->subscriptionPayment->payment_current_status == 'CAPTURED' || $this->plan->price == 0){
            Subscription::updateAll(['subscription_status' => self::STATUS_INACTIVE], ['and',  ['subscription_status' => self::STATUS_ACTIVE ] , ['restaurant_uuid' => $this->restaurant_uuid  ]]);
            $this->subscription_status = self::STATUS_ACTIVE;
          }


          if($this->plan->valid_for == 0)
             $this->subscription_end_at = null;

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {

        if($insert && $this->subscription_status ==  self::STATUS_ACTIVE){
          $restaurant_model = $this->restaurant;
          $restaurant_model->platform_fee = $this->plan->platform_fee;

          $restaurant_model->save(false);
        }


        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete() {


       $freePlan = Plan::find()->where(['valid_for' => 0])->one();

       $subscription = new Subscription();
       $subscription->restaurant_uuid = $this->restaurant_uuid;
       $subscription->plan_id = $freePlan->plan_id;
       $subscription->save(false);

        return parent::beforeDelete();
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionPayment()
    {
        return $this->hasOne(SubscriptionPayment::className(), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }


    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan() {
        return $this->hasOne(Plan::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
