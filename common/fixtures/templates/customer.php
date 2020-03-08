<?php

return [
    'customer_id' => $faker->numberBetween(1000,1000000),
    'customer_name' => $faker->firstname,
    'customer_phone_number' => $faker->phoneNumber,
    'customer_email' => $faker->companyEmail,
 ];

