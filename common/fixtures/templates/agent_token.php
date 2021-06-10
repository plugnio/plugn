<?php

return [
    'token_uuid' => $index+ 1,
    'agent_id' => $index +1,
    'token_value' => Yii::$app->getSecurity()->generateRandomString(),
    'token_device' => null,
    'token_device_id' => null,
    'token_status' => 10,
    'token_last_used_datetime' => $faker->date('Y-m-d H:i:s'),
    'token_expiry_datetime' => $faker->date('Y-m-d H:i:s'),
    'token_created_datetime'=> $faker->date('Y-m-d H:i:s')
];