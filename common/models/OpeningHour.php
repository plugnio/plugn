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
     * these are flags that are used by the form to dictate how the loop will handle each item
     */
    const UPDATE_TYPE_CREATE = 'create';
    const UPDATE_TYPE_UPDATE = 'update';
    const UPDATE_TYPE_DELETE = 'delete';

    const SCENARIO_BATCH_UPDATE = 'batchUpdate';


    private $_updateType;



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
            ['updateType', 'required', 'on' => self::SCENARIO_BATCH_UPDATE],
            ['updateType',
                'in',
                'range' => [self::UPDATE_TYPE_CREATE, self::UPDATE_TYPE_UPDATE, self::UPDATE_TYPE_DELETE],
                'on' => self::SCENARIO_BATCH_UPDATE
            ],
            [['day_of_week', 'is_closed'], 'integer'],
            [['open_at', 'close_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }


    public function getUpdateType()
    {
        if (empty($this->_updateType)) {
            if ($this->isNewRecord) {
                $this->_updateType = self::UPDATE_TYPE_CREATE;
            } else {
                $this->_updateType = self::UPDATE_TYPE_UPDATE;
            }
        }

        return $this->_updateType;
    }

    public function setUpdateType($value)
    {
        $this->_updateType = $value;
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

    public static function roundToNextHour($dateString) {
      $date = new \DateTime($dateString);
      $minutes = $date->format('i');
      if ($minutes > 0) {
          $date->modify("+1 hour");
          $date->modify('-'.$minutes.' minutes');
      }
          return $date->format('c');
    }


    public static function getAvailableTimeSlots($delivery_time, $store, $timeUnit) {

      $schedule_time = [];

      $currentWeekDay = date('w');

      for ($i = 0; $i <= OpeningHour::DAY_OF_WEEK_SATURDAY; $i++) {

         $currentWeekDay =  date('w',strtotime($i . " day"));
         $currentDate =  date('c',strtotime($i . " day"));
         $selectedDate =  date('c', strtotime('+ ' . $delivery_time . ' min'   ,strtotime($currentDate)));

         $getWorkingHours = OpeningHour::find()
                              ->where(['restaurant_uuid' => $store->restaurant_uuid])
                              ->andWhere(['day_of_week' => $currentWeekDay])
                              ->orderBy(['open_at' => SORT_ASC])
                              ->all();

            $timeSlots = [];


            foreach ($getWorkingHours as $key => $workingHours) {

              $startAt = date('c', strtotime($workingHours->open_at, strtotime($currentDate) ));

              
              // if($key == 0 )
              //     $startAt = date('c', strtotime("+". intval($store->schedule_interval)  . " min" ,strtotime($startAt)) );

              $startAt =  date('c', strtotime('+ ' . $delivery_time . ' min'   ,strtotime($startAt)));

              $startAt = static::roundToNextHour($startAt);

              // $startAt = date('c', strtotime("+ " . $i . " day" ,strtotime($startAt)));

              // if($workingHours->day_of_week ==  0)
              // die(json_encode(   date('c', strtotime($startAt) ) ));


              while (date('H:i:s', strtotime($startAt)) <= $workingHours->close_at && date('H:i:s', strtotime($startAt)) >= $workingHours->open_at ) {

                $endAt = date('c', strtotime("+". intval($store->schedule_interval)  . " min" ,strtotime($startAt)) );



                if ($workingHours->day_of_week == date('w', strtotime("today")) && date('c', strtotime("now")) < date('c', strtotime($startAt))) {

                  if( date('c',strtotime($startAt))  >  date('c', strtotime("now") + (intval($delivery_time) * 60)) ){

                  array_push($timeSlots, [
                      'date' =>  date('Y-m-d', strtotime($startAt) ),
                      'start_time' =>  date('c', strtotime($startAt) ) ,
                      'end_time' =>   date('c' , strtotime( $endAt) )
                  ]);
                  }
                } else if ($workingHours->day_of_week != date('w', strtotime("today")) ) {

                  array_push($timeSlots, [
                      'date' =>  date('Y-m-d', strtotime($startAt) ),
                      'start_time' =>  date('c', strtotime($startAt) ) ,
                      'end_time' =>   date('c' , strtotime( $endAt) )
                  ]);



                }




                if (date("Y/m/d", strtotime($endAt)) != date("Y/m/d", strtotime($startAt)) )
                  break;

                  $startAt = $endAt;

              }

            }


              array_push($schedule_time, [
                  'date' => $selectedDate,
                  'dayOfWeek' => $currentWeekDay,
                  'scheduleTimeSlots' => $timeSlots
              ]);


      }

      return $schedule_time;

      // if(count($scheduleOrder) > 0) {
      //   array_push($schedule_time, [
      //       'date' => date("c", strtotime($startAt)),
      //       'dayOfWeek' => date("w", strtotime($startTime)),
      //       'scheduleTimeSlots' => $scheduleOrder
      //   ]);
      // }


    }

    public function getDeliveryTimes($delivery_time, $date, $startTime) {

      // echo $startTime .  "\r\n";

        $time_interval = [];

        // for ($i = 0; date('H:i A', strtotime($this->open_at)) <= date('H:i A', strtotime($this->close_at)); $i++) {

        $opening_hrs = OpeningHour::find()
          ->where(['restaurant_uuid' => $this->restaurant_uuid])
          ->andWhere(['day_of_week' => date('w' , strtotime($startTime))])
          ->orderBy(['open_at' => SORT_ASC])
          ->all();



          $i = 0;
          foreach ($opening_hrs as $key => $model) {


            while ( date('H:i A', strtotime($startTime)) >= date('H:i A', strtotime($model->open_at)) && date('H:i A', strtotime($startTime)) <= date('H:i A', strtotime($model->close_at)) ) {


              echo  date('H:i A', strtotime($startTime))  .'Open =>>'.  date('H:i A', strtotime($model->open_at)) .'Close =>>'.  date('H:i A', strtotime($model->close_at)) . "\r\n";


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


$i++;
        }
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
