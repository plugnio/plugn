<?php

$item = Yii::$app->db->createCommand('SELECT * from `item` limit ' . $index . ',1')->queryAll();

$order = Yii::$app->db->createCommand('SELECT * from `order`')->queryOne();

return [
    'order_item_id' => $faker->numberBetween(1,100000),
    'order_uuid' => $order['order_uuid'],
    'item_uuid' => $item[0]['item_uuid'],
    'item_name' => $item[0]['item_name'],
    'item_price' => $item[0]['price'],
    'qty' => $faker->numberBetween(1,10),
    'customer_instruction' => $faker->firstname,
];