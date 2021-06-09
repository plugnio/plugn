<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'area_id' => $index + 1,
    'city_id' => $index + 1,
    'area_name' => $faker->word,
    'area_name_ar' => $faker->word,
    'latitude' => $faker->latitude(),
    'longitude' => $faker->longitude(),
];