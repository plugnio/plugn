<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "supplier".
 *
 * @property string $supplier_uuid
 * @property string $restaurant_uuid
 * @property string $name
 * @property string|null $contact_info Contact information for the supplier (email, phone)
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Restaurant $restaurant
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'name'], 'required'],//'supplier_uuid', 'created_at', 'updated_at'
            [['created_at', 'updated_at'], 'safe'],
            [['supplier_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['name', 'contact_info'], 'string', 'max' => 255],
            [['supplier_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }
    
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'supplier_uuid',
                ],
                'value' => function () {
                    if (!$this->supplier_uuid) {
                        $this->supplier_uuid = 'supplier_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->supplier_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className (),
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
            'supplier_uuid' => Yii::t('app', 'Supplier Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'name' => Yii::t('app', 'Name'),
            'contact_info' => Yii::t('app', 'Contact Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($model = 'common\models\RestaurantInventory')
    {
        return $this->hasOne($model::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
