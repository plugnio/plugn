<?php

return [
    'customer_id' => $faker->numberBetween(1000,1000000),
    'customer_name' => $faker->firstname,
    'customer_phone_number' => $faker->phoneNumber,
    'customer_email' => $faker->companyEmail,
    'customer_created_at' => $faker->date('Y-m-d H:i:s'),
    'customer_updated_at' => $faker->date('Y-m-d H:i:s'),
 ];

