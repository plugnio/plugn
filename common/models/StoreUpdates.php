<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "store_updates".
 *
 * @property string|null $store_update_uuid
 * @property string|null $restaurant_uuid
 * @property string $title
 * @property string $content
 * @property string $title_ar
 * @property string $content_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurantUu
 */
class StoreUpdates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_updates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content','title_ar', 'content_ar'], 'required'],
            [['content_ar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['store_update_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['title', 'title_ar'], 'string', 'max' => 100],
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'store_update_uuid',
                ],
                'value' => function() {
                    if(!$this->store_update_uuid)
                        $this->store_update_uuid = 'store_update_'. Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->store_update_uuid;
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
            'store_update_uuid' => Yii::t('app', 'Store Update Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'title_ar' => Yii::t('app', 'Title - Arabic'),
            'content_ar' => Yii::t('app', 'Content - Arabic'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
