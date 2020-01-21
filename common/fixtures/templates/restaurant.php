<?php

$index1 = $index % 50;//faker->unique()->numberBetween(0, 50);
$vendor_id = Yii::$app->db->createCommand('SELECT vendor_id from vendor limit '.$index1.',1')->queryScalar();

$area = Yii::$app->db->createCommand('SELECT * from area limit '.$index1.',1')->queryOne();

return [
    'restaurant_uuid' => 'rest_'.$faker->uuid,
    'vendor_id' => $vendor_id,
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
    'location' => $area['area_name'],
    'location_ar' => $area['area_name_ar'],
    'location_latitude' => $area['latitude'],
    'location_longitude' => $area['longitude'],
    'phone_number' => $faker->phoneNumber,
    'restaurant_created_at' => $faker->date('Y-m-d H:i:s'),
    'restaurant_updated_at' => $faker->date('Y-m-d H:i:s'),
];

