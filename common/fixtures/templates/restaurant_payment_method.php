<?php

$index1 = $index % 500; //faker->unique()->numberBetween(0, 500);
$index2 = $index % 50; //faker->unique()->numberBetween(0, 50);


$restaurant = Yii::$app->db->createCommand('SELECT * from restaurant')->queryOne();
$payment_method_id = Yii::$app->db->createCommand('SELECT payment_method_id from payment_method limit ' . $index2 . ',1')->queryScalar();

return [
    'payment_method_id' => $payment_method_id,
    'restaurant_uuid' => $restaurant['restaurant_uuid'],
];

