<?php

namespace common\models;

use Yii;
use common\models\CustomerBankDiscount;

/**
 * This is the model class for table "bank_discount".
 *
 * @property int $bank_discount_id
 * @property int|null $bank_id
 * @property string $restaurant_uuid
 * @property int $discount_type
 * @property int $discount_amount
 * @property int|null $bank_discount_status
 * @property string|null $valid_from
 * @property string|null $valid_until
 * @property int|null $max_redemption
 * @property int|null $limit_per_customer
 * @property int|null $minimum_order_amount
 * @property string|null $bank_discount_created_at
 * @property string|null $bank_discount_updated_at
 *
 * @property Bank $bank
 * @property Restaurant $restaurant
 * @property CustomerBankDiscount[] $customerBankDiscounts
 */
class BankDiscount extends \yii\db\ActiveRecord
{

    public $duration;

    //Values for `discount_type`
    const DISCOUNT_TYPE_PERCENTAGE = 1;
    const DISCOUNT_TYPE_AMOUNT = 2;


    //Values for `bank_discount_status`
    const BANK_DISCOUNT_STATUS_ACTIVE = 1;
    const BANK_DISCOUNT_STATUS_EXPIRED = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_id', 'discount_type', 'discount_amount', 'bank_discount_status', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'integer'],
            [['restaurant_uuid', 'discount_amount'], 'required'],
            [['valid_from', 'valid_until', 'bank_discount_created_at', 'bank_discount_updated_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_discount_id' => 'Bank Discount ID',
            'bank_id' => 'Bank ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'discount_type' => 'Discount Type',
            'discount_amount' => 'Discount Amount',
            'bank_discount_status' => 'Bank Discount Status',
            'valid_from' => 'Valid From',
            'valid_until' => 'Valid Until',
            'max_redemption' => 'Max Redemption',
            'limit_per_customer' => 'Limit Per Customer',
            'minimum_order_amount' => 'Minimum Order Amount',
            'bank_discount_created_at' => 'Bank Discount Created At',
            'bank_discount_updated_at' => 'Bank Discount Updated At',
        ];
    }


    public function getBankDiscountStatus() {
        switch ($this->bank_discount_status) {
            case self::BANK_DISCOUNT_STATUS_ACTIVE:
                return "Active";
                break;
            case self::BANK_DISCOUNT_STATUS_EXPIRED:
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
        if ($this->max_redemption != 0 && $this->getCustomerBankDiscounts()->count() >= $this->max_redemption)
            $isValid = false;

        //Make sure we're nt exceeding limit_per_customer
        if($this->limit_per_customer != 0 ){

          $customer_model = Customer::find()->where(['customer_phone_number' => $phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one();

          if ($customer_model) {
              $customerBankDiscount = CustomerBankDiscount::find()->where(['customer_id' => $customer_model->customer_id, 'bank_discount_id' => $this->bank_discount_id])->count();

              if ($customerBankDiscount) {

                  if ($customerBankDiscount >= $this->limit_per_customer)
                      $isValid = false;
              }

          }
        }


        return $isValid ? $this : false;
    }

    /**
     * Gets query for [[CustomerBankDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerBankDiscounts()
    {
        return $this->hasMany(CustomerBankDiscount::className(), ['bank_discount_id' => 'bank_discount_id']);
    }


    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
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

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id'])->via('restaurant');
    }

}
