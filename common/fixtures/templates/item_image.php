<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$index1 = $index % 50;//$faker->unique()->numberBetween(0, 500);

$item_uuid = Yii::$app->db->createCommand('SELECT item_uuid from item limit ' . $index1 . ',1')
    ->queryOne();

return [
    'item_image_id' => $index + 1,
    'item_uuid' => $item_uuid,
    'product_file_name' => 'YSXEB58IS0em3uODSnMZS6dXnhG0seaS.jpg'
];