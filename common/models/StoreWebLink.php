<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_web_link".
 *
 * @property int|null $web_link_id
 * @property string $restaurant_uuid
 *
 * @property Restaurant $restaurant
 * @property WebLink $webLink
 */
class StoreWebLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_web_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['web_link_id'], 'integer'],
            [['restaurant_uuid'], 'required'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['web_link_id'], 'exist', 'skipOnError' => true, 'targetClass' => WebLink::className(), 'targetAttribute' => ['web_link_id' => 'web_link_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'web_link_id' => 'Web Link ID',
            'restaurant_uuid' => 'Restaurant Uuid',
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[WebLink]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWebLink()
    {
        return $this->hasOne(WebLink::className(), ['web_link_id' => 'web_link_id']);
    }
}
