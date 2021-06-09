<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 50;//$faker->unique()->numberBetween(0, 500);

$restaurant= Yii::$app->db->createCommand('SELECT * from restaurant limit ' . $index1 . ',1')->queryOne();

return [
    'item_uuid' => $faker->numberBetween(1000,1000000),
    'restaurant_uuid' =>$restaurant['restaurant_uuid'],
    'item_name' => $faker->firstname,
    'item_name_ar' => $faker->firstname,
    'item_description' => $faker->sentence(4, true),
    'item_description_ar' =>  $faker->sentence(4, true),
    'sort_number' => $faker->numberBetween(1, 5),
    'stock_qty' => $faker->numberBetween(1, 15),
    'item_image' => '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png',
    'item_price' => $faker->numberBetween(1, 5),
    'item_created_at' => $faker->date('Y-m-d H:i:s'),
    'item_updated_at' => $faker->date('Y-m-d H:i:s')
 ];

