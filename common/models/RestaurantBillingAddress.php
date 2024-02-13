<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_billing_address".
 *
 * @property string $rba_uuid
 * @property int|null $country_id
 * @property string|null $restaurant_uuid
 * @property string $recipient_name
 * @property string $address_1
 * @property string|null $address_2
 * @property string|null $po_box
 * @property string|null $district
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip_code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Country $country
 * @property Restaurant $restaurantUu
 */
class RestaurantBillingAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_billing_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipient_name', 'address_1',], 'required'],//'rba_uuid',  'created_at', 'updated_at'
            [['country_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['rba_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['recipient_name', 'address_1', 'address_2', 'po_box', 'district', 'city', 'state', 'zip_code'], 'string', 'max' => 255],
            [['rba_uuid'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'rba_uuid',
                ],
                'value' => function () {
                    if (!$this->rba_uuid)
                        $this->rba_uuid = 'rba_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->rba_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rba_uuid' => Yii::t('app', 'Rba Uuid'),
            'country_id' => Yii::t('app', 'Country ID'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'recipient_name' => Yii::t('app', 'Recipient Name'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'po_box' => Yii::t('app', 'Po Box'),
            'district' => Yii::t('app', 'District'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'zip_code' => Yii::t('app', 'Zip Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            "country"
        ];
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
}
