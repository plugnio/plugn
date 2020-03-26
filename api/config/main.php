<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
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
                [// ItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/item',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'restaurant-menu',
                        'GET detail' => 'item-data',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                    ]
                ],
                  [// RestaurantController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant',
                    'pluralize' => false,
                    'patterns' => [
                        'GET branches/<id>' => 'list-all-restaurants-branches',
                        // OPTIONS VERBS
                        'OPTIONS branches/<id>' => 'options',
                    ]
                ],
                [// CityController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant-delivery',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list-all-cities',
                        'GET <id>' => 'get-delivered-area-data',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// PaymentMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment-method',
                    'pluralize' => false,
                    'patterns' => [
                        'GET <id>' => 'list-all-restaurants-payment-method',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order',
                    'pluralize' => false,
                    'patterns' => [
                        'POST <id>' => 'place-an-order',
                        'GET <id>' => 'order-look-up',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
