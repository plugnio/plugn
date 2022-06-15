<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'refunded_item_id' => $index + 1,
    'refund_id' => $index + 1,
    'order_item_id' => $index + 1,
    'order_uuid' => $index + 1,
    'item_uuid' => $index + 1,
    'item_name' => $faker->words(3, true),
    'item_name_ar' => $faker->words(3, true),
    'item_price' => $faker->numberBetween(10, 100),
    'qty' => $faker->numberBetween(10, 100)
];
