<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_addon".
 *
 * @property string|null $ra_uuid
 * @property string|null $addon_uuid
 * @property string|null $restaurant_uuid
 * @property string|null $created_at
 *
 * @property Addon $addon
 * @property Restaurant $restaurant
 */
class RestaurantAddon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_addon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['ra_uuid', 'addon_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['ra_uuid'], 'unique'],
            [['addon_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Addon::className(), 'targetAttribute' => ['addon_uuid' => 'addon_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'ra_uuid',
                ],
                'value' => function () {
                    if (!$this->ra_uuid) {
                        $this->ra_uuid = 'ra_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->ra_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
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
            'ra_uuid' => Yii::t('common', 'Ra Uuid'),
            'addon_uuid' => Yii::t('common', 'Addon Uuid'),
            'restaurant_uuid' => Yii::t('common', 'Restaurant Uuid'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Addon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddon($modelClass = "\common\models\Addon")
    {
        return $this->hasOne($modelClass::className(), ['addon_uuid' => 'addon_uuid']);
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
