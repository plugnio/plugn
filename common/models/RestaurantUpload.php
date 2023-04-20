<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_upload".
 *
 * @property string $upload_uuid
 * @property string $restaurant_uuid
 * @property string|null $path path in store built/www
 * @property string $filename
 * @property string|null $content
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Agent $createdBy
 * @property Restaurant $restaurant
 */
class RestaurantUpload extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_upload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'filename'], 'required'],
            [['content'], 'string'],
            [['created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['upload_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['path', 'filename'], 'string', 'max' => 255],
            [['upload_uuid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['created_by' => 'agent_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'upload_uuid',
                ],
                'value' => function () {
                    if (!$this->upload_uuid) {
                        // Get a unique uuid
                        $this->upload_uuid = 'upload_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();
                    }

                    return $this->upload_uuid;
                }
            ],
            /*[
                'class' => BlameableBehavior::className()
            ],*/
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'upload_uuid' => Yii::t('common', 'Upload Uuid'),
            'restaurant_uuid' => Yii::t('common', 'Restaurant Uuid'),
            'path' => Yii::t('common', 'Path'),
            'filename' => Yii::t('common', 'Filename'),
            'content' => Yii::t('common', 'Content'),
            'created_by' => Yii::t('common', 'Created By'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(
            $insert || (
                isset($changedAttributes["content"]) ||
                isset($changedAttributes["path"]) ||
                isset($changedAttributes["filename"])
            )
        ) {
            if($this->restaurant->site_id)
                Yii::$app->netlifyComponent->upgradeSite($this->restaurant->site_id);
        }

        return true;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy($modelClass = "\common\models\Agent")
    {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'created_by']);
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
