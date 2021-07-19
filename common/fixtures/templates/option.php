<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'option_id' => $index + 1,
    'item_uuid' => $index + 1,
    'min_qty' => $faker->numberBetween(0,1),
    'max_qty' => $faker->numberBetween(1,10),
    'option_name' => $faker->firstname,
    'option_name_ar' => $faker->firstname,
];

