<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tap_queue".
 *
 * @property int $tap_queue_id
 * @property string $restaurant_uuid
 * @property int|null $queue_status
 * @property string|null $queue_created_at
 * @property string|null $queue_updated_at
 * @property string|null $queue_start_at
 * @property string|null $queue_end_at
 *
 * @property Restaurant $restaurant
 */
class TapQueue extends \yii\db\ActiveRecord
{
      //Values for `queue_status`
      const QUEUE_STATUS_PENDING = 1;
      const QUEUE_STATUS_CREATING = 2;
      const QUEUE_STATUS_COMPLETE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tap_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'queue_status'], 'required'],
            [['queue_status'], 'integer'],
            [['queue_start_at', 'queue_end_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'queue_created_at',
                'updatedAtAttribute' => 'queue_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes) {

        if ($this->queue_status == self::QUEUE_STATUS_CREATING) {
          if($this->restaurant->createTapAccount()){
            $this->queue_status = self::QUEUE_STATUS_COMPLETE;
            if($this->save()){

              foreach ($this->restaurant->getOwnerAgent()->all() as $agent) {

                \Yii::$app->mailer->compose([
                       'html' => 'tap-created',
                           ], [
                       'store' => $this->restaurant,
                   ])
                   ->setFrom([\Yii::$app->params['supportEmail'] => 'Plugn'])
                   ->setTo([$agent->agent_email])
                   ->setSubject('Your TAP Payments account has been approved')
                   ->send();

              }
            }
          }
        }

      return parent::afterSave($insert, $changedAttributes);

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tap_queue_id' => 'Queue ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'queue_status' => 'Queue Status',
            'queue_created_at' => 'Queue Created At',
            'queue_updated_at' => 'Queue Updated At',
            'queue_start_at' => 'Queue Start At',
            'queue_end_at' => 'Queue End At',
        ];
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
