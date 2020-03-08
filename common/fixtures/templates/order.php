<?php

$index1 = $index % 20; //faker->unique()->numberBetween(0, 500);

$item_uuid = Yii::$app->db->createCommand('SELECT item_uuid from item limit '.$index1.',1')->queryScalar();
$restaurant = Yii::$app->db->createCommand('SELECT * from restaurant')->queryOne();
$restaurantDelivery = Yii::$app->db->createCommand('SELECT * from restaurant_delivery')->queryOne();
$area = Yii::$app->db->createCommand('SELECT * from area where area_id = ' . $restaurantDelivery['area_id'])->queryOne();
 
$restaurantPaymentMethod = Yii::$app->db->createCommand('SELECT * from restaurant_payment_method')->queryOne();
$paymentMethod = Yii::$app->db->createCommand('SELECT * from payment_method where payment_method_id = ' .$restaurantPaymentMethod['payment_method_id'])->queryOne();

$customer = Yii::$app->db->createCommand('SELECT * from customer')->queryOne();

return [
    'order_id' => $faker->numberBetween(1,100000),
    'customer_id' => $customer['customer_id'],
    'customer_phone_number' => $customer['customer_phone_number'],
    'customer_email' => $customer['customer_email'],
    'restaurant_uuid' => $restaurant['restaurant_uuid'],
    'area_id' => $restaurantDelivery['area_id'],
    'area_name'=> $area['area_name'],
    'area_name_ar' => $area['area_name_ar'],
    'unit_type' => 'House',
    'block' => $faker->numberBetween(1,10),
    'street' => $faker->numberBetween(1,10),
    'avenue' => $faker->numberBetween(1,10),
    'house_number' => $faker->numberBetween(1,10),
    'special_directions' => $faker->firstname,
    'customer_name' => $faker->firstname,
    'payment_method_id' => $restaurantPaymentMethod['payment_method_id'],
    'payment_method_name' => $paymentMethod['payment_method_name'],
];

