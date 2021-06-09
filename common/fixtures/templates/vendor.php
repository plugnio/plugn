<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$agent = Yii::$app->db->createCommand('SELECT * from  agent')->queryOne();

return [
    'agent_id' => $faker->numberBetween(100,1000),
    'agent_name' => $faker->firstName,
    'agent_auth_key' => Yii::$app->getSecurity()->generateRandomString(32),
    'agent_password_hash' => Yii::$app->getSecurity()->generatePasswordHash('123456'),
    'agent_password_reset_token' => Yii::$app->getSecurity()->generateRandomString(),
    'agent_email' => $faker->email,//TODO: add to data 'saoud@bawes.net'
    'agent_status' => 10,
    'agent_created_at' => $faker->date('Y-m-d H:i:s'),
    'agent_updated_at' => $faker->date('Y-m-d H:i:s'),
];
