<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

// Set LinkPager defaults
\Yii::$container->set('yii\widgets\LinkPager', [
    'options' => [
        'class' => 'pagination pagination-sm m-0',
    ],
    'disabledListItemSubTagOptions' => [
        'class' => 'page-link',
    ],
    'linkOptions' => [
        'class' => 'page-link',
    ],
        'linkContainerOptions' => [
        'class' => 'page-item',
    ],
]);

(new yii\web\Application($config))->run();
