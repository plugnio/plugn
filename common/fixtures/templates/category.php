<?php

$index1 = $index % 5;//faker->unique()->numberBetween(0, 50);

$vendor_id = Yii::$app->db->createCommand('SELECT vendor_id from vendor limit '.$index1.',1')->queryScalar();

$restaurant= Yii::$app->db->createCommand('SELECT restaurant_uuid from restaurant')->queryScalar();


return [
    'category_id' => $faker->numberBetween(1, 500),
    'restaurant_uuid' =>$restaurant,
    'category_name' => $faker->firstname,
    'category_name_ar' => $faker->firstname,
    'sort_number' => $faker->numberBetween(1, 5)
 ];

