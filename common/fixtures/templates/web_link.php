<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 50;

return [
    'web_link_id' => $index + 1,
    'restaurant_uuid' => $index1 + 1,
    'web_link_type' => 1,//website
    'url' => $faker->url,
    'web_link_title' => $faker->word,
    'web_link_title_ar' => $faker->word
];