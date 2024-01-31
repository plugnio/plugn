<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "restaurant_page".
 *
 * @property string $page_uuid
 * @property string $restaurant_uuid
 * @property string|null $title
 * @property string|null $title_ar
 * @property string|null $description
 * @property string|null $description_ar
 * @property string|null $slug
 * @property int|null $sort_number
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Agent $createdBy
 * @property Restaurant $restaurantUu
 * @property Agent $updatedBy
 */
class RestaurantPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'required'],
            [['description', 'description'], 'string'],
            [['created_by', 'updated_by', 'sort_number'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['page_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['title', 'title_ar', 'slug'], 'string', 'max' => 255],
            [['page_uuid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['created_by' => 'agent_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Agent::className(), 'targetAttribute' => ['updated_by' => 'agent_id']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'page_uuid',
                ],
                'value' => function () {
                    if (!$this->page_uuid) {
                        $this->page_uuid = 'page_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->page_uuid;
                }
            ],
            [
                'class' => BlameableBehavior::className()
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'uniqueValidator' => ['targetAttribute' => ['page_uuid', 'title']]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_uuid' => Yii::t('app', 'Page Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'title' => Yii::t('app', 'Title'),
            'title_ar' => Yii::t('app', 'Title Ar'),
            'description' => Yii::t('app', 'Description'),
            'description' => Yii::t('app', 'Description En'),
            'slug' => Yii::t('app', 'Slug'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert) {

                Yii::$app->eventManager->track('Store Pages Added', [
                    "page_title" => $this->title,
                    "page_title_arabic" => $this->title_ar,
                    "sort_number" => $this->sort_number
                ], null, $this->restaurant_uuid);
        }
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

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy($modelClass = "\common\models\Agent")
    {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'updated_by']);
    }
}
