<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-remail',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'remail\controllers',
    //'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@remail/modules/v1',
            'class' => 'remail\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            // Accept and parse JSON Requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [ // IncomingController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/incoming',
                    'pluralize' => false,
                    'patterns' => [
                        'POST' => 'receive',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
