<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'subscription_uuid' => $index + 1,
    'payment_method_id' => $index + 1,
    'payment_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'plan_id' => $index + 1,
    'subscription_status' => 10,
    'notified_email' => 1,
    'subscription_start_at' => $faker->date('Y-m-d H:i:s'),
    'subscription_end_at' => $faker->date('Y-m-d H:i:s'),
];
