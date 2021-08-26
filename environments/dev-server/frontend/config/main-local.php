<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://dashboard.dev.plugn.io',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    // 'session' => [
    //     // Use Redis as a cache
    //     'class' => 'yii\redis\Session',
    //     'redis' => [
    //         'hostname' => 'plugn-redis.0x1cgp.0001.euw2.cache.amazonaws.com',
    //         'port' => 6379,
    //         'database' => 4,
    //     ]
    // ],
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
