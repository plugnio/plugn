<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restock_history".
 *
 * @property string $history_uuid
 * @property string $restaurant_uuid
 * @property string|null $inventory_uuid
 * @property int|null $restocked_quantity Quantity restocked
 * @property string|null $restocked_at when the restock occurred
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RestaurantInventory $inventory
 * @property Restaurant $restaurant
 */
class RestockHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restock_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', "inventory_uuid"], 'required'],//'history_uuid', , 'created_at', 'updated_at'
            [['restocked_quantity'], 'integer'],
            [['restocked_at', 'created_at', 'updated_at'], 'safe'],
            [['history_uuid', 'restaurant_uuid', 'inventory_uuid'], 'string', 'max' => 60],
            [['history_uuid'], 'unique'],
            [['inventory_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => RestaurantInventory::className(), 'targetAttribute' => ['inventory_uuid' => 'inventory_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'history_uuid',
                ],
                'value' => function () {
                    if (!$this->history_uuid) {
                        $this->history_uuid = 'history_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->history_uuid;
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
            'history_uuid' => Yii::t('app', 'History Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'inventory_uuid' => Yii::t('app', 'Inventory Uuid'),
            'restocked_quantity' => Yii::t('app', 'Restocked Quantity'),
            'restocked_at' => Yii::t('app', 'Restocked At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Inventory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInventory($model = 'common\models\RestaurantInventory')
    {
        return $this->hasOne($model::className(), ['inventory_uuid' => 'inventory_uuid']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($model = 'common\models\Restaurant')
    {
        return $this->hasOne($model::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
