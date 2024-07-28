<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-crm',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'crm\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@crm/modules/v1',
            'class' => 'crm\modules\v1\Module',
        ]
    ],
    'components' => [
        'user' => [
          'identityClass' => 'crm\models\Staff',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [ // PingController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/ping',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'test',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [// StaffController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/staff',
                    'pluralize' => false,
                    'patterns' => [
                        'GET list' => 'list',
                        'GET' => 'detail',
                        //todo: update-email
                        'PUT update' => 'update-staff-profile',
                        'POST change-password' => 'change-password',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS list' => 'options',
                        'OPTIONS update' => 'options',
                        'OPTIONS change-password' => 'options',
                    ]
                ],
                [// StatsController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/stats',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS list' => 'options',
                    ]
                ],
                [// RestaurantController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant',
                    'pluralize' => false,
                    'patterns' => [
                        'GET list' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS list' => 'options',
                    ]
                ],
                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'POST login-auth0' => 'login-auth0',
                        'POST login-by-key' => 'login-by-key',
                        'PATCH update-password' => 'update-password',
                        'POST request-reset-password' => 'request-reset-password',
                        'POST update-email' => 'update-email',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS login-by-key' => 'options',
                        'OPTIONS login-auth0' => 'options',
                        'OPTIONS update-password' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS update-email' => 'options',
                    ]
                ],
                [// TicketController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/ticket',
                    'patterns' => [
                        'GET' => 'list',
                        'GET comments/<id>' => 'comments',
                        'GET <id>' => 'view',
                        'POST' => 'create',
                        'PATCH assign/<ticket_uuid>' => 'assign',
                        'PATCH comment/<ticket_uuid>' => 'comment',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS assign/<ticket_uuid>' => 'options',
                        'OPTIONS comment/<ticket_uuid>' => 'options',
                        'OPTIONS comments/<id>' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
