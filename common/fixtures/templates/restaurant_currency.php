<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'restaurant_currency_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'currency_id' => $index + 1,
    'created_at' => $faker->date('Y-m-d H:i:s'),
    'updated_at' => $faker->date('Y-m-d H:i:s'),
];
