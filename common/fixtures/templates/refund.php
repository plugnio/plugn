<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'refund_id' => $index + 1,
    'payment_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'order_uuid' => $index + 1,
    'refund_amount' => $faker->numberBetween(10, 100),
    'reason' => $faker->sentence(),
    'refund_status' => 'Pending',
    'refund_reference' => $index + 1
];
