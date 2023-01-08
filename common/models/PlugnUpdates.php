<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "plugn_updates".
 *
 * @property string|null $update_uuid
 * @property string $title
 * @property string $content
 * @property string $title_ar
 * @property string $content_ar
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class PlugnUpdates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plugn_updates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'title_ar', 'content_ar'], 'required'],
            [['content', 'content_ar'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['update_uuid'], 'string', 'max' => 60],
            [['title', 'title_ar'], 'string', 'max' => 100],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'update_uuid',
                ],
                'value' => function() {
                    if(!$this->update_uuid)
                        $this->update_uuid = 'update_'. Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->update_uuid;
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
            'update_uuid' => Yii::t('app', 'Update Uuid'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'title_ar' => Yii::t('app', 'Title - Arabic'),
            'content_ar' => Yii::t('app', 'Content - Arabic'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
