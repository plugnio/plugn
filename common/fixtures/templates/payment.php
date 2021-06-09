<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'payment_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'customer_id' => $index + 1,
    'order_uuid' => $index + 1,
    'payment_gateway_order_id' => $index + 1,
    'payment_gateway_transaction_id' => $index + 1,
    'payment_mode' => 'KNET',
    'payment_current_status' => 'captured',
    'payment_amount_charged' => $faker->numberBetween(100, 200),
    'payment_net_amount' =>$faker->numberBetween(100, 200),
    'payment_gateway_fee' => 2,
    'plugn_fee' => 2,
    'payment_udf1' => null,
    'payment_udf2' => null,
    'payment_udf3' => null,
    'payment_udf4' => null,
    'payment_udf5' => null,
    'payment_created_at' => $faker->dateTime(),
    'payment_updated_at' => $faker->dateTime(),
    'received_callback' => 1,
    'response_message' => $faker->sentence(),
    'payment_token' => md5($index + 1)
];
