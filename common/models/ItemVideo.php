<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "item_video".
 *
 * @property int $item_video_id
 * @property string $item_uuid
 * @property string $youtube_video_id
 * @property string|null $product_file_name
 * @property int $sort_number
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $itemUu
 */
class ItemVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_uuid', 'youtube_video_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_uuid'], 'string', 'max' => 300],
            [['sort_number'], 'number'],
            [['youtube_video_id'], 'string', 'max' => 20],
            [['product_file_name'], 'string', 'max' => 255],
            [['item_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_uuid' => 'item_uuid']],
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
                'class' => TimestampBehavior::className(),
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
            'item_video_id' => Yii::t('app', 'Item Video ID'),
            'item_uuid' => Yii::t('app', 'Item Uuid'),
            'youtube_video_id' => Yii::t('app', 'Youtube Video ID'),
            'product_file_name' => Yii::t('app', 'Product File Name'),
            'sort_number' => Yii::t('app','Sort number'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\common\models\Item")
    {
        return $this->hasOne($modelClass::className(), ['item_uuid' => 'item_uuid']);
    }
}
