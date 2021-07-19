<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'queue_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'queue_status' => $faker->randomElement([1, 2, 3]),
    'queue_created_at' => $faker->date('Y-m-d H:i:s'),
    'queue_updated_at' => $faker->date('Y-m-d H:i:s'),
    'queue_start_at' => $faker->date('Y-m-d H:i:s'),
    'queue_end_at' => $faker->date('Y-m-d H:i:s'),
];
