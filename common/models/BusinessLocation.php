<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_location".
 *
 * @property int $business_location_id
 * @property string $restaurant_uuid
 * @property string $country_id
 * @property string $business_location_name
 * @property string $business_location_name_ar
 * @property int $support_pick_up
 * @property float $business_location_tax
 * @property string $address
 * @property string $mashkor_branch_id
 * @property string $armada_api_key
 * @property float|null $latitude
 * @property float|null $longitude
 * @property boolean $is_deleted
 * @property Restaurant $restaurant
 * @property Country $country
 * @property DeliveryZone[] $deliveryZones
 */
class BusinessLocation extends \yii\db\ActiveRecord
{
    const SCENARIO_DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'country_id', 'business_location_name', 'business_location_name_ar'], 'required'],
            [['country_id' , 'support_pick_up', 'is_deleted'], 'integer'],
            [['support_pick_up'], 'default', 'value' => 0],
            [['latitude', 'longitude'], 'number'],
            [['business_location_tax'], 'default', 'value' => 0],
            [['business_location_tax'], 'number', 'min' => 0, 'max' => 100],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['business_location_name', 'business_location_name_ar','address','armada_api_key', 'mashkor_branch_id'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'business_location_id' => Yii::t('app','Business Location ID'),
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'country_id' => Yii::t('app','Located in'),
            'business_location_name' => Yii::t('app','Location Name'),
            'business_location_name_ar' => Yii::t('app','Location Name in Arabic'),
            'support_pick_up' => Yii::t('app','Support Pick Up'),
            'business_location_tax' => Yii::t('app','Tax / VAT')
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();

        $scenarios['delete'] = ['is_deleted'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function extraFields() {
        return [
            'country',
            'deliveryZones',
            'deliveryZones.country',
            'deliveryZones.areas',
            'totalDeliveryZoneCountry'
        ];
    }


    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_DELETE ) {


            $businessLocationDeliverZones = $this->getDeliveryZones()->all();


            foreach ($businessLocationDeliverZones as $key => $deliveryZone) {
              \common\models\AreaDeliveryZone::updateAll([
                  'is_deleted' => 1
              ], [
                  'delivery_zone_id' => $deliveryZone->delivery_zone_id,
                  'restaurant_uuid' => $deliveryZone->restaurant_uuid,
              ]);
            }

            \common\models\DeliveryZone::updateAll([
                'is_deleted' => 1
            ], [
                'business_location_id' => $this->business_location_id,
                'restaurant_uuid' => $this->restaurant_uuid,
            ]);



        }

    }



    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
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

    //
    // /**
    //  * Gets query for [[DeliveryZones]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZones()
    // {
    //     return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])->joinWith(['areas']);
    // }

    /**
     * Gets query for [[DeliveryZone]].
     * @param string $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['business_location_id' => 'business_location_id'])->where(['is_deleted' => 0]);
    }

    /**
     * Gets query for [[DeliveryZone]].
     * @param $countryId
     * @param string $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZoneByCountryId($countryId, $modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['business_location_id' => 'business_location_id'])
            ->andWhere(['delivery_zone.country_id' => 1]);
    }

     /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['delivery_zone_id' => 'delivery_zone_id'])
            ->via('deliveryZones');
    }

    /**
     * delivery zone countries
     * @param string $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZoneCountries($modelClass = "\common\models\Country")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id'])
            ->via('deliveryZones');
    }

    public function getTotalDeliveryZoneCountry($modelClass = "\common\models\Country")
    {
        return $this->getDeliveryZoneCountries($modelClass)->count();
    }

    public static function find() {
        return new query\BusinessLocationQuery(get_called_class());
    }
}
