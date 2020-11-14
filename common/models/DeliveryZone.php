<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "delivery_zone".
 *
 * @property int $delivery_zone_id
 * @property int $business_location_id
 * @property string $business_location_name
 * @property string $business_location_name_ar
 * @property int $support_delivery
 * @property int $support_pick_up
 * @property int|null $delivery_time
 * @property float|null $delivery_fee
 * @property float|null $min_charge
 *
 * @property AreaDeliveryZone[] $areaDeliveryZones
 * @property Area[] $areas
 * @property BusinessLocation $businessLocation
 */
class DeliveryZone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_zone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_location_id', 'business_location_name', 'business_location_name_ar', 'support_delivery', 'support_pick_up'], 'required'],
            [['business_location_id', 'support_delivery', 'support_pick_up', 'delivery_time'], 'integer'],
            [['delivery_fee', 'min_charge'], 'number'],
            [['business_location_name', 'business_location_name_ar'], 'string', 'max' => 255],
            [['business_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessLocation::className(), 'targetAttribute' => ['business_location_id' => 'business_location_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delivery_zone_id' => 'Delivery Zone ID',
            'business_location_id' => 'Business Location ID',
            'business_location_name' => 'Business Location Name',
            'business_location_name_ar' => 'Business Location Name Ar',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
            'delivery_time' => 'Delivery Time',
            'delivery_fee' => 'Delivery Fee',
            'min_charge' => 'Min Charge',
        ];
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones()
    {
        return $this->hasMany(AreaDeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('area_delivery_zone', ['delivery_zone_id' => 'delivery_zone_id']);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation()
    {
        return $this->hasOne(BusinessLocation::className(), ['business_location_id' => 'business_location_id']);
    }
}
