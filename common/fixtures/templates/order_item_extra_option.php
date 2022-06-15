<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 5; //faker->unique()->numberBetween(0, 500);

$orderItem = Yii::$app->db->createCommand('SELECT * from `order_item` limit ' . $index . ',1')->queryAll();
$extraOption = Yii::$app->db->createCommand('SELECT * from `extra_option` limit ' . $index . ',1')->queryAll();

return [
    'order_item_extra_option_id' => $index + 1,
    'order_item_id' => $index + 1,
    'extra_option_id' => $index + 1,
    'extra_option_name' => $faker->word,
    'extra_option_name_ar' => $faker->word,
    'extra_option_price' => $faker->numberBetween (10, 100),
    'qty' => $faker->numberBetween (10, 100)
];