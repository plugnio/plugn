<?php

$index1 = $index % 5;//faker->unique()->numberBetween(0, 50);

$vendor = Yii::$app->db->createCommand('SELECT * from  vendor')->queryOne();

return [
    'restaurant_uuid' => 'rest_'.$faker->uuid,
    'vendor_id' => $vendor['vendor_id'],
    'name' => $faker->firstName,
    'name_ar' => $faker->firstName,
    'tagline' => $faker->sentence(4, true),  // generate a sentence with 7 words,
    'tagline_ar' => $faker->sentence(4, true),  // generate a sentence with 7 words,
    'restaurant_status' => 1,  // generate a sentence with 7 words,
    'thumbnail_image' => 'thumbnail_image',  // generate a sentence with 7 words,
    'logo' => 'logo',  // generate a sentence with 7 words,
    'support_delivery' => 0,
    'support_pick_up' => 0,
    'delivery_fee' => $faker->numberBetween(1, 2),
    'min_charge' => $faker->numberBetween(1, 3),
    'phone_number' => $faker->phoneNumber,
    'restaurant_created_at' => $faker->date('Y-m-d H:i:s'),
    'restaurant_updated_at' => $faker->date('Y-m-d H:i:s'),
];