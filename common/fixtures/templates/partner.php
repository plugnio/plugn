<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'partner_uuid' => $index + 1,
    'username' => 'user_' . ($index + 1),
    'partner_auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'partner_password_hash' => Yii::$app->getSecurity()->generatePasswordHash(12345),
    'partner_password_reset_token' => null,
    'partner_email' => $faker->email,
    'partner_status' => 10,
    'referral_code' => '123112',
    'partner_iban' => $faker->word(),
    'commission' => 10,
    'benef_name' => $faker->name(),
    'bank_id' => $index + 1,
    'partner_phone_number' => $faker->phoneNumber(),
    'partner_phone_number_country_code' => 91,
    'partner_created_at' => $faker->date('Y-m-d H:i:s'),
    'partner_updated_at' => $faker->date('Y-m-d H:i:s'),
];