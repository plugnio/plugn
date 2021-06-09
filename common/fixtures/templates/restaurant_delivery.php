<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 500; //faker->unique()->numberBetween(0, 500);
$index2 = $index % 50; //faker->unique()->numberBetween(0, 50);


$restaurant_uuid = Yii::$app->db->createCommand('SELECT restaurant_uuid from restaurant')->queryScalar();
$area_id = Yii::$app->db->createCommand('SELECT area_id from area limit ' . $index2 . ',1')->queryScalar();

return [
    'area_id' => $area_id,
    'restaurant_uuid' => $restaurant_uuid,
    'delivery_time' => $faker->numberBetween(0,60),
    'delivery_time_ar' => $faker->numberBetween(0,60),
    'delivery_fee' => $faker->numberBetween(0,60),
    'min_charge' => $faker->numberBetween(0,60),
];

