<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'currency_id' => $index + 1,
    'title' => $faker->word .' ('. $faker->currencyCode . ')',
    'code' => $faker->currencyCode
];
