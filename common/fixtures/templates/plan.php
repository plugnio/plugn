<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'plan_id' => $index + 1,
    'name' => $faker->word,
    'price' => $faker->numberBetween(10, 100),
    'valid_for' => $faker->numberBetween(10, 100),
    'platform_fee' => $faker->numberBetween(5, 50),
    'description' => $faker->sentence()
];
