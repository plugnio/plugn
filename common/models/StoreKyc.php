<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "store_kyc".
 *
 * @property string $kyc_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $place_of_birth
 * @property string|null $marital_status
 * @property string|null $residence_region
 * @property string|null $source_of_income
 * @property string|null $occupation
 * @property string|null $expected_annual_sales
 * @property string|null $sales_channels
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Restaurant $restaurantUu
 */
class StoreKyc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_kyc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['kyc_uuid', 'created_at', 'updated_at'], 'required'],
            [['place_of_birth', 'marital_status', 'residence_region', 'source_of_income', 'occupation', 'expected_annual_sales', 'sales_channels'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['kyc_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['place_of_birth', 'marital_status', 'residence_region', 'source_of_income', 'occupation', 'expected_annual_sales', 'sales_channels'], 'string', 'max' => 255],
            [['kyc_uuid'], 'unique'],
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'kyc_uuid',
                ],
                'value' => function () {
                    if (!$this->kyc_uuid)
                        $this->kyc_uuid = 'kyc_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->kyc_uuid;
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
            'kyc_uuid' => Yii::t('app', 'Kyc Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'place_of_birth' => Yii::t('app', 'Place Of Birth'),
            'marital_status' => Yii::t('app', 'Marital Status'),
            'residence_region' => Yii::t('app', 'Residence Region'),
            'source_of_income' => Yii::t('app', 'Source Of Income'),
            'occupation' => Yii::t('app', 'Occupation'),
            'expected_annual_sales' => Yii::t('app', 'Expected Annual Sales'),
            'sales_channels' => Yii::t('app', 'Sales Channels'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
