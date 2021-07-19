<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'bank_id' => $index + 1,
    'bank_name' => $faker->word,
    'bank_iban_code' => $faker->word,
    'bank_swift_code' => $faker->word,
    'bank_address' => $faker->address,
    'bank_transfer_type' => 'NEFT',//todo: check value
    'bank_created_at' => $faker->date('Y-m-d H:i:s'),
    'bank_updated_at' => $faker->date('Y-m-d H:i:s'),
    'deleted' => 0
];