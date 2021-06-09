<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'opening_hour_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'day_of_week' => $faker->randomElement([0, 1, 2, 3, 4, 5, 6]),
    'open_at' => $faker->time(),
    'close_at' => $faker->time(),
    'is_closed' => 0
];
