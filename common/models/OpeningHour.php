<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "opening_hour".
 *
 * @property int $opening_hour_id
 * @property string $restaurant_uuid
 * @property int $day_of_week
 * @property string $open_at
 * @property string $close_at
 * @property string $is_closed
 *
 * @property Restaurant $restaurant
 */
class OpeningHour extends \yii\db\ActiveRecord {

    //Values for `day_of_week`
    const DAY_OF_WEEK_SUNDAY = 0;
    const DAY_OF_WEEK_MONDAY = 1;
    const DAY_OF_WEEK_TUESDAY = 2;
    const DAY_OF_WEEK_WEDNESDAY = 3;
    const DAY_OF_WEEK_THURSDAY = 4;
    const DAY_OF_WEEK_FRIDAY = 5;
    const DAY_OF_WEEK_SATURDAY = 6;

    public $open_24_hrs;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'opening_hour';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'day_of_week', 'open_at', 'close_at'], 'required'],
            [['day_of_week', 'is_closed'], 'integer'],
            [['open_at', 'close_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * Returns String value of day of week
     * @return string
     */
    public function getDayOfWeek() {
        switch ($this->day_of_week) {
            case self::DAY_OF_WEEK_SATURDAY:
                return "Saturday";
                break;
            case self::DAY_OF_WEEK_SUNDAY:
                return "Sunday";
                break;
            case self::DAY_OF_WEEK_MONDAY:
                return "Monday";
                break;
            case self::DAY_OF_WEEK_TUESDAY:
                return "Tuesday";
                break;
            case self::DAY_OF_WEEK_WEDNESDAY:
                return "Wednesday";
                break;
            case self::DAY_OF_WEEK_THURSDAY:
                return "Thursday";
                break;
            case self::DAY_OF_WEEK_FRIDAY:
                return "Friday";
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['restaurant_uuid']);
        unset($fields['opening_hour_id']);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'opening_hour_id' => 'Opening Hour ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'day_of_week' => 'Day Of Week',
            'open_at' => 'Open at',
            'close_at' => 'Close at',
        ];
    }

    public function getDeliveryTimes($delivery_time, $date, $startTime) {


        $time_interval = [];


        for ($i = 0; date('H:i A', strtotime($startTime)) <= date('H:i A', strtotime($this->close_at)); $i++) {

            $endTime = date('c', strtotime($startTime) + intval($this->restaurant->schedule_interval) * 60);


            if ($this->day_of_week == date('w', strtotime("today")) && date('c', strtotime("now")) < date('c', strtotime($startTime))) {

              if( date('c',strtotime($startTime))  >  date('c', strtotime("now") + (intval($delivery_time) * 60)) ){
                array_push($time_interval, [
                    'date' =>  $date,
                    'start_time' =>  date('c', strtotime($startTime)) ,
                    'end_time' =>   date('c' , strtotime($endTime))
                ]);
              }

            } else if ($this->day_of_week != date('w', strtotime("today")) ) {


                  $time_interval[$i] = [
                    'date' =>  $date,
                    'start_time' =>  date('c', strtotime($startTime)) ,
                    'end_time' =>   date('c' , strtotime($endTime))
                  ];


            }



            if (date("Y/m/d", strtotime($startTime) + intval($this->restaurant->schedule_interval) * 60) != date("Y/m/d", strtotime($startTime)))
                break;


            $startTime = date('c', strtotime($startTime) + intval($this->restaurant->schedule_interval) * 60);



        }


        return $time_interval;
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
