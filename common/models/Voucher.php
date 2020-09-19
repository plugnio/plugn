<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
use common\models\Customer;
use common\models\CustomerVoucher;

/**
 * This is the model class for table "voucher".
 *
 * @property int $voucher_id
 * @property string $restaurant_uuid
 * @property string $description
 * @property string $description_ar
 * @property string $code
 * @property int $discount_type
 * @property int $discount_amount
 * @property int|null $voucher_status
 * @property string|null $valid_from
 * @property string|null $valid_until
 * @property int|null $max_redemption
 * @property int|null $limit_per_customer
 * @property int|null $minimum_order_amount
 * @property string|null $voucher_created_at
 * @property string|null $voucher_updated_at
 *
 * @property CustomerVoucher[] $customerVouchers
 * @property Order[] $orders
 * @property Restaurant $restaurantUu
 */
class Voucher extends \yii\db\ActiveRecord {

    public $duration;

    //Values for `discount_type`
    const DISCOUNT_TYPE_PERCENTAGE = 1;
    const DISCOUNT_TYPE_AMOUNT = 2;


    //Values for `voucher_status`
    const VOUCHER_STATUS_ACTIVE = 1;
    const VOUCHER_STATUS_EXPIRED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'voucher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid',  'discount_type', 'code' ,'discount_amount', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'required'],
            [['discount_type', 'voucher_status', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'integer'],
            [['valid_from', 'valid_until', 'duration'], 'safe'],
            ['discount_type', 'in', 'range' => [self::DISCOUNT_TYPE_PERCENTAGE, self::DISCOUNT_TYPE_AMOUNT]],
            ['voucher_status', 'in', 'range' => [self::VOUCHER_STATUS_ACTIVE, self::VOUCHER_STATUS_EXPIRED]],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['discount_amount'], 'number', 'min' => 0],
            [['voucher_created_at', 'voucher_updated_at'], 'safe'],
            [['code','description','description_ar'], 'string', 'max' => 255],
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
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'voucher_created_at',
                'updatedAtAttribute' => 'voucher_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'voucher_id' => 'Voucher ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'description' => 'Description',
            'description_ar' => 'Description in Arabic',
            'code' => 'Code',
            'discount_type' => 'Discount Type',
            'discount_amount' => 'Discount Amount',
            'voucher_status' => 'Voucher Status',
            'valid_from' => 'Valid From',
            'valid_until' => 'Valid Until',
            'voucher_created_at' => 'Created At',
            'voucher_updated_at' => 'Updated At',
            'max_redemption' => 'Max Redemption',
            'limit_per_customer' => 'Limit Per Customer',
            'minimum_order_amount' => 'Minimum Order Amount',
        ];
    }

    public function getVoucherStatus() {
        switch ($this->voucher_status) {
            case self::VOUCHER_STATUS_ACTIVE:
                return "Active";
                break;
            case self::VOUCHER_STATUS_EXPIRED:
                return "Expired";
                break;
        }

        return "Couldnt find a status";
    }

    public function isValid($phone_number) {
        $isValid = true;

        //Make sure today within selected duration
        if ($this->valid_from && $this->valid_until) {
            $today = date('Y-m-d');
            $today = date('Y-m-d', strtotime($today));

            $validFrom = date('Y-m-d', strtotime($this->valid_from));
            $validUntil = date('Y-m-d', strtotime($this->valid_until));

            if (($today >= $validFrom) && ($today <= $validUntil))
                $isValid = true;
            else
                $isValid = false;
        }

        //Make sure we're nt exceeding max_redemption
        if ($this->max_redemption != 0 && $this->getCustomerVouchers()->count() >= $this->max_redemption)
            $isValid = false;

        //Make sure we're nt exceeding limit_per_customer
        if($this->limit_per_customer != 0 ){

          $customer_model = Customer::find()->where(['customer_phone_number' => $phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one();

          if ($customer_model) {
              $customerVoucher = CustomerVoucher::find()->where(['customer_id' => $customer_model->customer_id, 'voucher_id' => $this->voucher_id])->count();

              if ($customerVoucher) {

                  if ($customerVoucher >= $this->limit_per_customer)
                      $isValid = false;
              }

          }
        }


        return $isValid ? $this : false;
    }


    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers() {
        return $this->hasMany(CustomerVoucher::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['voucher_id' => 'voucher_id']);
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
