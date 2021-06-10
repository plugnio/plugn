<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'order_item_id' => $index + 1,
    'order_uuid' => $index + 1,
    'item_uuid' => $index + 1,
    'item_name' => $faker->word,
    'item_name_ar' => $faker->word,
    'item_price' => $faker->numberBetween (10, 100),
    'qty' => $faker->numberBetween(1,10),
    'customer_instruction' => $faker->sentence,
];