<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'assignment_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'agent_id' => $index + 1,
    'business_location_id' => $index + 1,
    'assignment_agent_email' => $faker->email,
    'assignment_created_at' => $faker->date('Y-m-d H:i:s'),
    'assignment_updated_at' => $faker->date('Y-m-d H:i:s'),
    'role' => $faker->randomElement([1,2,3]),
    'email_notification' => 1,
    'reminder_email' => 1,
    'receive_weekly_stats' => 1
];