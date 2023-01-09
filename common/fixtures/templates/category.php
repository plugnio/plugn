<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'category_id' => $index + 1,
    'restaurant_uuid' => $index + 1,
    'title' => $faker->word,
    'title_ar' => $faker->word,
    'subtitle' => $faker->words(3, true),
    'subtitle_ar' => $faker->words(3, true),
    'category_image' => null,//todo: sample image path
    'slug' => $faker->slug,
    'sort_number' => $faker->numberBetween(1, 5)
 ];
