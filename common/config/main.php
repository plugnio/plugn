<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'currencyCode' => 'KWD',
            'defaultTimeZone' => 'Asia/Kuwait',
        ],
        'cloudinaryManager' => [
            'class' => 'common\components\CloudinaryManager',
            'cloud_name' => 'vendor',
            'api_key' => '533686683298673',
            'api_secret' => 'MTpkOszwAgcSR-yFPneAdLWDkZs'
        ],
    ],
];
