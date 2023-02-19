<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "customer_campaign".
 *
 * @property string $campaign_uuid
 * @property string $template_uuid
 * @property string|null $restaurant_uuid
 * @property int|null $progress
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Restaurant $restaurant
 * @property VendorEmailTemplate $template
 */
class CustomerCampaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_uuid'], 'required'],
            [['progress', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['campaign_uuid', 'template_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['campaign_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['template_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => VendorEmailTemplate::className(), 'targetAttribute' => ['template_uuid' => 'template_uuid']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'campaign_uuid',
                ],
                'value' => function () {
                    if (!$this->campaign_uuid) {
                        $this->campaign_uuid = 'campaign_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();
                    }

                    return $this->campaign_uuid;
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
            'campaign_uuid' => Yii::t('app', 'Campaign Uuid'),
            'template_uuid' => Yii::t('app', 'Template Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'progress' => Yii::t('app', 'Progress'),
            'status' => Yii::t('app', 'Status'),
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

    /**
     * Gets query for [[Template]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate($modelClass = "\common\models\VendorEmailTemplate")
    {
        return $this->hasOne($modelClass::className(), ['template_uuid' => 'template_uuid']);
    }
}
