<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$charged = $faker->numberBetween($min = 1000, $max = 9000);

return [
    'payment_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'subscription_uuid' => $index + 1,
    'payment_gateway_order_id' => $index + 1,
    'payment_gateway_transaction_id' => $index + 1,
    'payment_mode' => 'KNET',
    'payment_current_status' => 'captured',
    'payment_amount_charged' => $charged,
    'payment_net_amount' => $charged - 2,
    'payment_gateway_fee' => 2,
    'payment_udf1' => null,
    'payment_udf2' => null,
    'payment_udf3' => null,
    'payment_udf4' => null,
    'payment_udf5' => null,
    'payment_created_at' => null,
    'payment_updated_at' => null,
    'received_callback' => 1,
    'response_message' => $faker->sentence,
    'payment_token' => $faker->word
];