<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'category_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'category_name' => $faker->firstname,
    'category_name_ar' => $faker->firstname,
    'sort_number' => $faker->numberBetween(1, 5)
 ];
