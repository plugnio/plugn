<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'customer_id' => $index + 1,
    'customer_name' => $faker->firstname,
    'customer_phone_number' => $faker->phoneNumber,
    'country_code' => $faker->countryCode,
    'customer_email' => $faker->companyEmail,
    'customer_auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'customer_password_hash' => Yii::$app->getSecurity()->generatePasswordHash('12345'),
    'customer_password_reset_token' => null,
    'customer_email_verification' => 1,
    'customer_created_at' => $faker->date('Y-m-d H:i:s'),
    'customer_updated_at' => $faker->date('Y-m-d H:i:s'),
 ];


