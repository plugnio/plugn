<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'extra_option_id' => $index + 1,
    'option_id' => $index + 1,
    'extra_option_name' => $faker->firstname,
    'extra_option_name_ar' => $faker->firstname,
    'extra_option_price' => $faker->numberBetween(1,5),
    'stock_qty' => $faker->numberBetween (10,100)
];

