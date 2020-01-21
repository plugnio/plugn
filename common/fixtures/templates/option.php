<?php

$index1 = $index % 20; //faker->unique()->numberBetween(0, 500);

$item_uuid = Yii::$app->db->createCommand('SELECT item_uuid from item limit '.$index1.',1')->queryScalar();

return [
    'option_id' => $faker->numberBetween(1,100000),
    'item_uuid' => $item_uuid,
    'is_required' => $faker->numberBetween(0,1),
    'max_qty' => $faker->numberBetween(1,10),
    'option_name' => $faker->firstname,
    'option_name_ar' => $faker->firstname,
];

