  
<?php
$vendor = Yii::$app->db->createCommand('SELECT * from  vendor')->queryOne();

return [
    'vendor_id' => $faker->numberBetween(1,1000),
    'vendor_name' => $faker->firstName,
    'vendor_auth_key' => Yii::$app->getSecurity()->generateRandomString(32),
    'vendor_password_hash' => Yii::$app->getSecurity()->generatePasswordHash('123456'),
    'vendor_password_reset_token' => Yii::$app->getSecurity()->generateRandomString(),
    'vendor_email' => $faker->companyEmail,
    'vendor_status' => 10,
    'vendor_created_at' => $faker->date('Y-m-d H:i:s'),
    'vendor_updated_at' => $faker->date('Y-m-d H:i:s'),
];