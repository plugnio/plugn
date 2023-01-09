<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'item_uuid' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'item_name' => $faker->firstname,
    'item_name_ar' => $faker->firstname,
    'item_description' => $faker->sentence(4, true),
    'item_description_ar' =>  $faker->sentence(4, true),
    'sort_number' => $faker->numberBetween(1, 5),
    'stock_qty' => $faker->numberBetween(1, 15),
    'track_quantity' => $faker->numberBetween (1, 15),
    'sku' => $faker->word,
    'barcode' => $faker->iban (),
    'unit_sold' => $faker->numberBetween (100, 200),
    'item_image' => '8e1lUGUnUKbjFbq2ZNbd80Pg9xYkLrLs.png',
    'item_price' => $faker->numberBetween(1, 5),
    'compare_at_price' => $faker->numberBetween(1, 5),
    'item_created_at' => $faker->date('Y-m-d H:i:s'),
    'item_updated_at' => $faker->date('Y-m-d H:i:s'),
    'item_status' => 10,
    'prep_time' => 11,
    'slug' => $faker->slug,
    'prep_time_unit' => 'hrs'
 ];

