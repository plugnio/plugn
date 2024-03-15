<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'ticket_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'agent_id' => $index + 1,
    "ticket_detail" => $faker->sentence(4, true),
    "ticket_status" => 0
];