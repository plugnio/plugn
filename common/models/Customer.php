<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "customer".
 *
 * @property int $customer_id
 * @property int $restaurant_uuid
 * @property string $customer_name
 * @property string $country_code
 * @property string $customer_phone_number
 * @property string|null $customer_email
 * @property string $customer_created_at
 * @property string $customer_updated_at
 * @property string $civil_id
 * @property string $section
 * @property string $class
 *
 * @property Order[] $orders
 * @property CustomerVoucher[] $customerVouchers
 * @property Restaurant $restaurant
 */
class Customer extends \yii\db\ActiveRecord {

    //for report
    public $totalSpent;
    public $totalOrder;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['customer_name', 'customer_phone_number','restaurant_uuid', 'country_code'], 'required'],
            //'customer_email', 
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'unique'],
            [['customer_email'], 'unique'],
            [['customer_email'], 'email'],
            [['country_code'], 'integer'],
            [['customer_phone_number'], 'string', 'min' => 5, 'max' => 20],
            [['customer_created_at','customer_updated_at'], 'safe'],
            [['customer_name', 'customer_email'], 'string', 'max' => 255],
            [['civil_id', 'section','class'], 'string', 'max' => 255], //Temp fields
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'customer_created_at',
                'updatedAtAttribute' => 'customer_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'customer_id' => Yii::t('app','Customer ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant UUID'),
            'customer_name' => Yii::t('app','Customer Name'),
            'customer_phone_number' => Yii::t('app','Phone Number'),
            'country_code' => Yii::t('app','Country Code'),
            'customer_email' => Yii::t('app','Customer Email'),
            'customer_created_at' => Yii::t('app','Customer Created At'),
            'customer_updated_at' => Yii::t('app','Customer Updated At'),
        ];
    }

    public static function getTotalCustomersByWeek()
    {
        $customer_data = [];

        $date_start = strtotime ('-6 days');//date('w')

        for ($i = 0; $i < 7; $i++) {
            $date = date ('Y-m-d', $date_start + ($i * 86400));

            $customer_data[date ('w', strtotime ($date))] = array(
                'day' => date ('D', strtotime ($date)),
                'total' => 0
            );
        }

        $rows = Customer::find()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere(new Expression("DATE(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAYNAME(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('w', strtotime ($result['customer_created_at']))] = array(
                'day' => date ('D', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::find()
            ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public static function getTotalCustomersByMonth()
    {
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-1 month')).'-1';

        for ($i = 1; $i <= date('t', strtotime($date_start)); $i++) {
            $customer_data[$i] = array(
                'day'   => $i,
                'total' => 0
            );
        }

        $rows = Customer::find()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
            ->groupBy(new Expression('DAY(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('j', strtotime ($result['customer_created_at']))] = array(
                'day' => (int) date ('j', strtotime ($result['customer_created_at'])),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::find()
            ->andWhere('`customer_created_at` >= (NOW() - INTERVAL 1 MONTH)')
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    public static function getTotalCustomersByMonths($months)
    {
        $customer_data = [];

        $date_start = date('Y') . '-' . date('m', strtotime('-'.$months.' month')) . '-1';
        $date_end = date('Y-m-d', strtotime('last day of previous month'));
        //date('Y-m-d');//date('Y') . '-' . date('m') . '-1';

        for ($i = 0; $i <= $months; $i++) {

            $month = date('m', strtotime('-'.($months - $i).' month'));

            $customer_data[$month] = array(
                'month'   => date('F', strtotime('-'.($months - $i).' month')),
                'total' => 0
            );
        }

        $rows = Customer::find()
            ->select(new Expression('customer_created_at, COUNT(*) as total'))
            ->andWhere('`customer_created_at` >= (NOW() - INTERVAL '.$months.' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
            ->groupBy(new Expression('MONTH(customer_created_at)'))
            ->asArray()
            ->all();

        foreach ($rows as $result) {
            $customer_data[date ('m', strtotime ($result['customer_created_at']))] = array(
                'month' => Yii::t('app', date ('F', strtotime ($result['customer_created_at']))),
                'total' => (int) $result['total']
            );
        }

        $number_of_all_customer_gained = Customer::find()
            ->andWhere('`customer_created_at` >= (NOW() - INTERVAL '.$months.' MONTH)')
//            ->andWhere('DATE(`customer_created_at`) >= DATE("'.$date_start.'") AND DATE(`customer_created_at`) <= DATE("'.$date_end.'")')
            ->count();

        return [
            'customer_chart_data' => array_values($customer_data),
            'number_of_all_customer_gained' => (int) $number_of_all_customer_gained
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
        ->activeOrders($this->restaurant_uuid);
    }

    /**
     * @param string $modelClass
     * @return mixed
     */
    public function getTotalSpent($modelClass = "\common\models\Order") {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
        ->activeOrders($this->restaurant_uuid)
        ->sum('total_price');
    }

    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers($modelClass = "\common\models\CustomerVoucher")
    {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
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

    public static function find() {
        return new query\CustomerQuery(get_called_class());
    }
}
