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
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'unique'],
            [['customer_email'], 'unique'],
            [['country_code'], 'integer'],
            [['customer_phone_number'], 'string', 'min' => 5, 'max' => 20],
            [['customer_created_at','customer_updated_at'], 'safe'],
            [['customer_name', 'customer_email'], 'string', 'max' => 255],
            //[['civil_id', 'section','class'], 'string', 'max' => 255], //Temp fields
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
            'customer_id' => 'Customer ID',
            'restaurant_uuid' => 'Restaurant UUID',
            'customer_name' => 'Customer Name',
            'customer_phone_number' => 'Phone Number',
            'country_code' => 'Country Code',
            'customer_email' => 'Customer Email',
            'customer_created_at' => 'Customer Created At',
            'customer_updated_at' => 'Customer Updated At',
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
