<?php

namespace common\models;

use Yii;
use common\models\CustomerBankDiscount;
use yii\db\Expression;

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
            [['restaurant_uuid', 'discount_amount', 'discount_type', 'discount_amount'], 'required'],
            ['bank_discount_status', 'in', 'range' => [self::BANK_DISCOUNT_STATUS_ACTIVE, self::BANK_DISCOUNT_STATUS_EXPIRED]],
            ['discount_type', 'in', 'range' => [self::DISCOUNT_TYPE_PERCENTAGE, self::DISCOUNT_TYPE_AMOUNT]],
            [['valid_from', 'valid_until', 'bank_discount_created_at', 'bank_discount_updated_at','duration'], 'safe'],
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
            'bank_discount_id' => Yii::t('app','Bank Discount ID'),
            'bank_id' => Yii::t('app','Bank ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'discount_type' => Yii::t('app','Discount Type'),
            'discount_amount' => Yii::t('app','Discount Amount'),
            'bank_discount_status' => Yii::t('app','Bank Discount Status'),
            'valid_from' => Yii::t('app','Valid From'),
            'valid_until' => Yii::t('app','Valid Until'),
            'max_redemption' => Yii::t('app','Max Redemption'),
            'limit_per_customer' => Yii::t('app','Limit Per Customer'),
            'minimum_order_amount' => Yii::t('app','Minimum Order Amount'),
            'bank_discount_created_at' => Yii::t('app','Bank Discount Created At'),
            'bank_discount_updated_at' => Yii::t('app','Bank Discount Updated At'),
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

    /**
     * @return array|false|int[]|string[]
     */
    public function extraFields()
    {
        $fields = parent::fields();

        $fields['voucherChartData'] = function() {
            return $this->voucherChartData();
        };

        return array_merge($fields, [
          'restaurant',
          'currency',
          'bank',
          'customerBankDiscounts'
        ]);
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

          $customer_model = Customer::find()->where(['customer_phone_number' => '+' . $phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one();

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
     * return voucher usage by months
     * @return array
     */
    public function voucherChartData() {

        $voucher_chart_data = [];

        /*$date_start = $this->valid_from;

        if(strtotime($this->valid_until) < time()) {
            $date_end = $this->valid_until;
        } else {
            $date_end = date('Y') . '-' . date('m') . '-1';
        }

        $months = $this->getMonthsBetween($date_start, $date_end);

        for ($i = 0; $i < $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $voucher_chart_data[$month] = array(
                'month'   => date('F', strtotime('-'.($months - $i).' month')),
                'total' => 0
            );
        }*/

        $rows = $this->getOrders()
            ->activeOrders()
            ->select ('order_created_at, COUNT(*) as total')
            //->andWhere('DATE(`order_created_at`) >= DATE("'.$date_start.'") AND DATE(`order_created_at`) < DATE("'.$date_end.'")')
            ->groupBy (new Expression('MONTH(order_created_at), YEAR(order_created_at)'))
            ->orderBy('order_created_at')
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $voucher_chart_data[date ('m', strtotime ($result['order_created_at']))] = array(
                'month' => Yii::t('app', date ('M', strtotime ($result['order_created_at']))),
                'total' => (int) $result['total']
            );
        }

        return array_values($voucher_chart_data);
    }

    /**
     * Gets query for [[CustomerBankDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerBankDiscounts($modelClass = "\common\models\CustomerBankDiscount")
    {
        return $this->hasMany($modelClass::className(), ['bank_discount_id' => 'bank_discount_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['bank_discount_id' => 'bank_discount_id']);
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank($modelClass = "\common\models\Bank")
    {
        return $this->hasOne($modelClass::className(), ['bank_id' => 'bank_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id'])->via('restaurant');
    }
}
