  
<?php
$vendor = Yii::$app->db->createCommand('SELECT * from  vendor')->queryOne();

return [
    'vendor_id' => $faker->numberBetween(100,1000),
    'restaurant_uuid' => 'rest_fdb95572-1b66-36be-93d4-9853a5e50a60',
    'vendor_name' => $faker->firstName,
    'vendor_auth_key' => Yii::$app->getSecurity()->generateRandomString(32),
    'vendor_password_hash' => Yii::$app->getSecurity()->generatePasswordHash('123456'),
    'vendor_password_reset_token' => Yii::$app->getSecurity()->generateRandomString(),
    'vendor_email' => 'saoud@bawes.net',
    'vendor_status' => 10,
    'vendor_created_at' => $faker->date('Y-m-d H:i:s'),
    'vendor_updated_at' => $faker->date('Y-m-d H:i:s'),
];