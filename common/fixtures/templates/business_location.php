<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'business_location_id' => $index + 1,
    'country_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'business_location_name' => $faker->streetName,
    'business_location_name_ar' => $faker->streetName,
    'support_pick_up' => $faker->randomElement([0,1]),
    'business_location_tax' => $faker->numberBetween(10,20),
    'address' => $faker->address,
    'latitude' => $faker->latitude,
    'longitude' => $faker->longitude,
    'mashkor_branch_id' => null,
    'armada_api_key' => null,
    'diggipack_customer_id' => null,
    'is_deleted' => 0
];
