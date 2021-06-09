<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'session' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 4,
            ]
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/~saoud/plugn/plugn-yii2/frontend/web/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
      $config['bootstrap'][] = 'debug';
      $config['modules']['debug'] = [
          'class' => 'yii\debug\Module',
      ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
