<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_delivery_zone".
 *
 * @property int $delivery_zone_id
 * @property int $country_id
 * @property int $area_id
 *
 * @property Area $area
 * @property Country $country
 * @property DeliveryZone $deliveryZone
 */
class AreaDeliveryZone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_delivery_zone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_zone_id', 'country_id'], 'required'],
            [['delivery_zone_id', 'country_id', 'area_id'], 'integer'],
            [['delivery_zone_id', 'area_id'], 'unique', 'targetAttribute' => ['delivery_zone_id', 'area_id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['delivery_zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryZone::className(), 'targetAttribute' => ['delivery_zone_id' => 'delivery_zone_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'area_delivery_zone_id' => 'Area Delivery Zone ID',
            'delivery_zone_id' => 'Delivery Zone ID',
            'country_id' => 'Country ID',
            'area_id' => 'Area ID',
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
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone()
    {
        return $this->hasOne(DeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id']);
    }
}
