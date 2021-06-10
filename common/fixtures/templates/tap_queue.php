<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 50;

$restaurant_uuid = Yii::$app->db->createCommand('SELECT * from restaurant limit '.$index1.',1')->queryOne();

return [
    'tap_queue_id' => $index + 1,
    'restaurant_uuid' => $restaurant_uuid,
    'queue_status' => $faker->randomElement([1,2,3]),
    'queue_created_at' => $faker->date('Y-m-d H:i:s'),
    'queue_updated_at'=> $faker->date('Y-m-d H:i:s'),
    'queue_start_at'=> $faker->date('Y-m-d H:i:s'),
    'queue_end_at'=> $faker->date('Y-m-d H:i:s'),
];