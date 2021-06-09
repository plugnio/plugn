<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'customer_id' => $index + 1,
    'customer_name' => $faker->firstname,
    'customer_phone_number' => $faker->phoneNumber,
    'customer_email' => $faker->companyEmail,
    'customer_created_at' => $faker->date('Y-m-d H:i:s'),
    'customer_updated_at' => $faker->date('Y-m-d H:i:s'),
 ];

