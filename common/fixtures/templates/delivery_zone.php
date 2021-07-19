<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'delivery_zone_id' => $index + 1,
    'country_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'business_location_id' => $index + 1,
    'delivery_time' => '11',
    'time_unit' => 'hrs',
    'delivery_fee' => $faker->numberBetween(10, 100),
    'min_charge' => $faker->numberBetween(10, 100),
    'delivery_zone_tax' => $faker->numberBetween(10, 100)
];
