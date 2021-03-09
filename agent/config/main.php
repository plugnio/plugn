<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-agent',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'agent\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@agent/modules/v1',
            'class' => 'agent\modules\v1\Module',
        ]
    ],
    'components' => [
        'user' => [
          'identityClass' => 'agent\models\Agent',
          'enableAutoLogin' => false,
          'enableSession' => false,
          'loginUrl' => null
        ],
        'request' => [
            'enableCookieValidation' => false,
            // Accept and parse JSON Requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [

                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order',
                    'pluralize' => false,
                    'patterns' => [
                        'GET <store_uuid>' => 'get-all-pending-orders',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <store_uuid>' => 'options'
                    ]
                ],

                [ // AuthController
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'v1/auth',
                   'pluralize' => false,
                   'patterns' => [
                       'GET login' => 'login',

                       // OPTIONS VERBS
                       'OPTIONS login' => 'options'
                   ]
               ],
            ],
        ],
    ],
    'params' => $params,
];
