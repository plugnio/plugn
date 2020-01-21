<?php

$index1 = $index % 50;//faker->unique()->numberBetween(0, 50);
$vendor_id = Yii::$app->db->createCommand('SELECT vendor_id from vendor limit '.$index1.',1')->queryScalar();

$restaurant= Yii::$app->db->createCommand('SELECT * from restaurant')->queryOne();


return [
    'category_id' => $faker->numberBetween(1, 500),
    'restaurant_uuid' =>$restaurant['restaurant_uuid'],
    'category_name' => $faker->firstname,
    'category_name_ar' => $faker->firstname,
    'sort_number' => $faker->numberBetween(1, 5)
 ];

