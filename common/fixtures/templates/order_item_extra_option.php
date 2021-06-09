<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 5; //faker->unique()->numberBetween(0, 500);

$orderItem = Yii::$app->db->createCommand('SELECT * from `order_item` limit ' . $index . ',1')->queryAll();
$extraOption = Yii::$app->db->createCommand('SELECT * from `extra_option` limit ' . $index . ',1')->queryAll();

return [
    'order_item_extra_option_id' => $faker->numberBetween(1,100000),
    'order_item_id' => $orderItem[0]['order_item_id'],
    'extra_option_id' => $extraOption[0]['extra_option_id'],
    'extra_option_name' => $extraOption[0]['extra_option_name'],
    'extra_option_name_ar' => $extraOption[0]['extra_option_name_ar'],
    'extra_option_price' => $extraOption[0]['extra_option_price'],
];