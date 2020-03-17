<?php

$index1 = $index % 5;//faker->unique()->numberBetween(0, 50);

$vendor = Yii::$app->db->createCommand('SELECT * from  vendor')->queryOne();

return [
    'restaurant_uuid' => 'rest_fdb95572-1b66-36be-93d4-9853a5e50a60',
    'vendor_id' => $vendor['vendor_id'],
    'name' => 'Lavina',
    'name_ar' => $faker->firstName,
    'tagline' => $faker->sentence(4, true),  // generate a sentence with 7 words,
    'tagline_ar' => $faker->sentence(4, true),  // generate a sentence with 7 words,
    'restaurant_status' => 1,  // generate a sentence with 7 words,
    'thumbnail_image' => 'uiAiNXR9gxH7LzPB2D4JJGVGAaQaoYE9.jpg',
    'logo' => 'lG8AZqroaynHiHYSnMJ-ZZsF_bc0C6KY.jpg',
    'support_delivery' => 1,
    'support_pick_up' => 1,
    'phone_number' => $faker->phoneNumber,
    'restaurant_created_at' => $faker->date('Y-m-d H:i:s'),
    'restaurant_updated_at' => $faker->date('Y-m-d H:i:s'),
];