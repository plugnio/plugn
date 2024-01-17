<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


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
 * @property int|null $exclude_discounted_items
 * @property boolean is_public
 * @property int is_deleted
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
    const DISCOUNT_TYPE_FREE_DELIVERY = 3;

    //Values for `voucher_status`
    const VOUCHER_STATUS_ACTIVE = 1;
    const VOUCHER_STATUS_EXPIRED = 2;

    const SCENARIO_UPDATE_STATUS = 'update-status';
    const SCENARIO_DELETE = 'delete';

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
            [['restaurant_uuid',  'discount_type', 'code' , 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'required'],
            ['discount_amount', 'required', 'when' => function($model) {
               return $model->discount_type != self::DISCOUNT_TYPE_FREE_DELIVERY;
            }],
            [['discount_type', 'voucher_status', 'max_redemption', 'limit_per_customer', 'minimum_order_amount'], 'integer'],
            [['valid_from', 'valid_until', 'duration'], 'safe'],
            ['discount_type', 'in', 'range' => [self::DISCOUNT_TYPE_PERCENTAGE, self::DISCOUNT_TYPE_AMOUNT, self::DISCOUNT_TYPE_FREE_DELIVERY]],
            ['voucher_status', 'in', 'range' => [self::VOUCHER_STATUS_ACTIVE, self::VOUCHER_STATUS_EXPIRED]],
            [['restaurant_uuid'], 'string', 'max' => 60],
            /*['discount_amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'when' => function($model) {
                return $model->discount_type != self::DISCOUNT_TYPE_FREE_DELIVERY;
            }],*/
            [['exclude_discounted_items', 'is_deleted', 'is_public'], 'integer'],
            [['discount_amount'], 'integer', 'min' => 1, 'when' => function($model) {
                return $model->discount_type != self::DISCOUNT_TYPE_FREE_DELIVERY;
            }],
            [['voucher_created_at', 'voucher_updated_at', 'is_public'], 'safe'],
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
            'voucher_id' => Yii::t('app','Voucher ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'description' => Yii::t('app','Description'),
            'description_ar' => Yii::t('app','Description in Arabic'),
            'code' => Yii::t('app','Code'),
            'discount_type' => Yii::t('app','Discount Type'),
            'discount_amount' => Yii::t('app','Discount Amount'),
            'voucher_status' => Yii::t('app','Voucher Status'),
            'valid_from' => Yii::t('app','Valid From'),
            'valid_until' => Yii::t('app','Valid Until'),
            'voucher_created_at' => Yii::t('app','Created At'),
            'voucher_updated_at' => Yii::t('app','Updated At'),
            'max_redemption' => Yii::t('app','Max Redemption'),
            'limit_per_customer' => Yii::t('app','Limit Per Customer'),
            'minimum_order_amount' => Yii::t('app','Minimum Order Amount'),
            'exclude_discounted_items' => Yii::t('app', 'exclude_discounted_items'),
            'is_public' => Yii::t('app', 'Is Public?'),
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

    public function getDiscountType() {

        switch ($this->discount_type) {
            case self::DISCOUNT_TYPE_PERCENTAGE:
                return "Percentage";
                break;
            case self::DISCOUNT_TYPE_AMOUNT:
                return "Amount";
                break;
            case self::DISCOUNT_TYPE_FREE_DELIVERY:
                return "Free delivery";
                break;
        }

        return "Couldnt find discount type";
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

          if($this->discount_type == self::DISCOUNT_TYPE_FREE_DELIVERY)
            $this->discount_amount  = null;

          return true;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(YII_ENV == 'prod') {
            $props = [
                "code" => $this->code,
                "voucher_description_english" => $this->description,
                "voucher_description_arabic" => $this->description_ar,
                "discount_type" => $this->discount_type,
                "discount_amount_percentage" => $this->discount_type == self::DISCOUNT_TYPE_PERCENTAGE ? $this->discount_amount: null,
                "discount_amount" => $this->discount_type == self::DISCOUNT_TYPE_AMOUNT ? $this->discount_amount: null,
                "discount_items_excluded" => $this->exclude_discounted_items,
                "is_public" => $this->is_public,
                "start_date" => $this->valid_from,
                "end_date" => $this->valid_until,
                "max_redemptions" => $this->max_redemption,
                "limit_per_customer" => $this->limit_per_customer,
                "minimum_order_amount" => $this->minimum_order_amount
            ];

             Yii::$app->eventManager->track("Voucher Added", $props); 
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if(!parent::beforeDelete ()) {
            return false;
        }

        CustomerVoucher::deleteAll (['voucher_id' => $this->voucher_id]);

        return true;
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

          $customer_model = Customer::find()->where(['customer_phone_number' => '+' .  $phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one();

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

    public function extraFields()
    {
        $fields = parent::extraFields();

        $fields['voucherChartData'] = function() {
            return $this->voucherChartData();
        };

        return $fields;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return array_merge($scenarios, [
            self::SCENARIO_UPDATE_STATUS => ['restaurant_status'],
            self::SCENARIO_DELETE => ['is_deleted']
        ]);
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
     * Function will give you the difference months between two dates
     *
     * @param string $start_date
     * @param string $end_date
     * @return int|null
     */
    public function getMonthsBetween($start_date, $end_date)
    {
        $startDate = new \DateTime($start_date);
        $endDate = new \DateTime($end_date);
        $interval = $startDate->diff($endDate);
        $months = ($interval->y * 12) + $interval->m;

        return $startDate > $endDate ? -$months : $months;
    }

    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers($modelClass = "\common\models\CustomerVoucher") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id'])
        ->activeOrders($this->restaurant_uuid);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
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

    public static function find()
    {
        return new query\VoucherQuery(get_called_class());
    }
}
