<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'country_id' => $index + 1,
    'country_name' => $faker->country,
    'country_name_ar'=> $faker->country,
    'iso' => $faker->countryCode,
    'emoji' => null,
    'country_code' => $faker->countryCode
];
