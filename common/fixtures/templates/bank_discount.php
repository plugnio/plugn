<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'bank_discount_id' => $index + 1,
    'bank_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'discount_type' => $faker->randomElement([1,2]),
    'discount_amount' => $faker->numerBetween(10, 20),
    'bank_discount_status' => 1,
    'valid_from' => $faker->dateTime(),
    'valid_until' => $faker->dateTime(),
    'max_redemption' => $faker->numberBetween(10,100),
    'limit_per_customer' => $faker->numberBetween(10,100),
    'minimum_order_amount' => $faker->numberBetween(10,100),
    'bank_discount_created_at' => $faker->dateTime(),
    'bank_discount_updated_at' => $faker->dateTime(),
];
