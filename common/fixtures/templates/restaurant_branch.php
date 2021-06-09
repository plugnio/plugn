<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'restaurant_branch_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'branch_name_en' => $faker->streetName,
    'branch_name_ar' => $faker->streetName,
    'prep_time' => $faker->numberBetween(0, 12)
];
