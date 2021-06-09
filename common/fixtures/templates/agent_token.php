<?php

return [
    'token_uuid' => $index+ 1,
    'agent_id' => $index +1,
    'token_value' => Yii::$app->getSecurity()->generateRandomString(),
    'token_device' => null,
    'token_device_id' => null,
    'token_status' => 10,
    'token_last_used_datetime' => $faker->datetime(),
    'token_expiry_datetime' => $faker->dateTime(),
    'token_created_datetime'=> $faker->dateTime(),
];