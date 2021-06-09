<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'agent_id' => $index + 1,
    'agent_name' => $faker->firstName,
    'agent_email' => $faker->email,
    'agent_auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'agent_password_hash' => Yii::$app->getSecurity()->generatePasswordHash('12345'),
    'agent_password_reset_token' => null,
    'agent_status' => 10,
    'email_notification' => 1,
    'reminder_email' => 1,
    'agent_created_at' => $faker->date('Y-m-d H:i:s'),
    'agent_updated_at' => $faker->date('Y-m-d H:i:s'),
    'receive_weekly_stats' => 1
];
