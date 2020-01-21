<?php

$index1 = $index % 5; //faker->unique()->numberBetween(0, 500);
$index2 = $index % 10; //faker->unique()->numberBetween(0, 50);


$category_id = Yii::$app->db->createCommand('SELECT category_id from category')->queryScalar();
$item_uuid = Yii::$app->db->createCommand('SELECT item_uuid from item limit ' . $index2 . ',1')->queryScalar();

return [
    'category_id' => $category_id,
    'item_uuid' => $item_uuid
];

