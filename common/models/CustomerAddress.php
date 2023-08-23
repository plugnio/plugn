<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_address".
 *
 * @property int $address_id
 * @property int $customer_id
 * @property int|null $area_id
 * @property int|null $city_id
 * @property int|null $country_id
 * @property string|null $unit_type
 * @property int|null $house_number
 * @property int|null $floor
 * @property string|null $apartment
 * @property string|null $building
 * @property string|null $block
 * @property string|null $street
 * @property string|null $avenue
 * @property string|null $office
 * @property string|null $postalcode
 * @property string|null $address_1
 * @property string|null $address_2
 * @property string|null $special_directions
 * @property string|null $delivery_instructions
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Area $area
 * @property City $city
 * @property Country $country
 * @property Customer $customer
 */
class CustomerAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'city_id', 'country_id', 'postalcode', 'unit_type'], 'required'],
            [['address_id', 'customer_id', 'area_id', 'city_id', 'country_id', 'house_number', 'floor'], 'integer'],
            [['special_directions', 'delivery_instructions'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['unit_type', 'postalcode'], 'string', 'max' => 50],
            [['apartment', 'building', 'block', 'street', 'avenue', 'office'], 'string', 'max' => 100],
            [['address_1', 'address_2'], 'string', 'max' => 255],
            [['address_id'], 'unique'],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'city_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
        ];
    }

    public function extraFields()
    {
        $fields = parent::extraFields();

        return array_merge($fields, [
            'country',
           // 'state',
            'city',
            'area'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'address_id' => Yii::t('app', 'Address ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'area_id' => Yii::t('app', 'Area ID'),
            'city_id' => Yii::t('app', 'City ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'unit_type' => Yii::t('app', 'Unit Type'),
            'house_number' => Yii::t('app', 'House Number'),
            'floor' => Yii::t('app', 'Floor'),
            'apartment' => Yii::t('app', 'Apartment'),
            'building' => Yii::t('app', 'Building'),
            'block' => Yii::t('app', 'Block'),
            'street' => Yii::t('app', 'Street'),
            'avenue' => Yii::t('app', 'Avenue'),
            'office' => Yii::t('app', 'Office'),
            'postalcode' => Yii::t('app', 'Postalcode'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'special_directions' => Yii::t('app', 'Special Directions'),
            'delivery_instructions' => Yii::t('app', 'Delivery Instructions'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }
}
