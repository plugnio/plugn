<?php

$index1 = $index % 500; //faker->unique()->numberBetween(0, 500);
$index2 = $index % 50; //faker->unique()->numberBetween(0, 50);


$restaurant= Yii::$app->db->createCommand('SELECT * from restaurant')->queryOne();
$area_id = Yii::$app->db->createCommand('SELECT area_id from area limit ' . $index2 . ',1')->queryScalar();

return [
    'area_id' => $area_id,
    'restaurant_uuid' => $restaurant['restaurant_uuid'],
];

