<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'area_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'delivery_time' => $faker->numberBetween(0,60),
    'delivery_time_ar' => $faker->numberBetween(0,60),
    'delivery_fee' => $faker->numberBetween(0,60),
    'min_charge' => $faker->numberBetween(0,60),
];

