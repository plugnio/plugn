<?php

$index1 = $index % 20; //faker->unique()->numberBetween(0, 500);

$option_id = Yii::$app->db->createCommand('SELECT option_id from `option` limit ' . $index1 . ',1')->queryScalar();
                                                
return [
    'extra_option_id' => $faker->numberBetween(1,100000),
    'option_id' => $option_id,
    'extra_option_name' => $faker->firstname,
    'extra_option_name_ar' => $faker->firstname,
    'extra_option_price' => $faker->numberBetween(1,5),
];

