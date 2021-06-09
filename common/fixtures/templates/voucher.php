<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'voucher_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'code' => $faker->word,
    'description' => $faker->sentence,
    'description_ar' => $faker->sentence,
    'discount_type' => $faker->randomElement([1,2,3]),
    'discount_amount' => $faker->numberBetween($min = 1000, $max = 9000),
    'voucher_status' => 1,
    'valid_from' => '1992-12-12',
    'valid_until' => '2992-12-12',
    'max_redemption' => 100,
    'limit_per_customer' => 10,
    'minimum_order_amount' => $faker->numberBetween($min = 1000, $max = 9000),
    'voucher_created_at' => $faker->dateTime(),
    'voucher_updated_at' => $faker->dateTime(),
];
