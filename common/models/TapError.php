<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use function Psy\debug;

/**
 * This is the model class for table "tap_error".
 *
 * @property string $tap_error_uuid
 * @property string $restaurant_uuid
 * @property string|null $title
 * @property string|null $message
 * @property string|null $text
 * @property int|null $issue_logged No of time issue logged
 * @property int|null $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Restaurant $restaurantUu
 */
class TapError extends \yii\db\ActiveRecord
{
    public $statusName;

    const STATUS_PENDING = 10;
    const STATUS_FIXED = 1;
    const STATUS_CLOSED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tap_error';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'required'],
            [['text'], 'string'],
            [['issue_logged', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tap_error_uuid', 'restaurant_uuid'], 'string', 'max' => 60],
            [['title', 'message'], 'string', 'max' => 255],
            [['tap_error_uuid'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    public static function getStatusArray() {
        return [
            10 => "Pending",
            1 => "Fixed",
            2 => "Closed"
        ];
    }

    public function getStatusName() {
        switch ($this->status) {
            case 10:
                return "Pending";
            case 1:
                return "Fixed";
            case 2:
                return "Closed";
            default:
                return "Unknown";
        }
    }


    /**
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'tap_error_uuid',
                ],
                'value' => function () {
                    if (!$this->tap_error_uuid)
                        $this->tap_error_uuid = 'tap_error_' . Yii::$app->db->createCommand ('SELECT uuid()')->queryScalar ();

                    return $this->tap_error_uuid;
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
            'tap_error_uuid' => Yii::t('app', 'Tap Error Uuid'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'title' => Yii::t('app', 'Title'),
            'message' => Yii::t('app', 'Message'),
            'text' => Yii::t('app', 'Text'),
            'issue_logged' => Yii::t('app', 'Issue Logged'),
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
}
