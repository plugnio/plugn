<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'payment_method_id' => $index + 1,
    'payment_method_name' => $faker->firstName,
    'payment_method_name_ar' => $faker->firstName,
    'source_id' => $faker->word
];

