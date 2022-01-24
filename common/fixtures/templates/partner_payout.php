<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'partner_payout_uuid' => $index + 1,
    'partner_uuid' => $index + 1,
    'amount' => $faker->numberBetween (100, 200),
    'created_at' => $faker->date('Y-m-d H:i:s'),
    'updated_at' => $faker->date('Y-m-d H:i:s'),
    'payout_status' => 1,
    'transfer_benef_iban' => $faker->word(),
    'transfer_benef_name' => $faker->words(2),
    'bank_id' => $index + 1,
    'transfer_file' => null,
];