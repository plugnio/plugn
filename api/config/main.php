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
                [// RestaurantDeliveryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant-delivery',
                    'pluralize' => false,
                    'patterns' => [
                        'GET delivery-area/<restaurant_uuid>' => 'list-all-cities',
                        'GET <id>' => 'delivered-area-data',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS delivery-area/<restaurant_uuid>' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// PaymentMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment',
                    'pluralize' => false,
                    'patterns' => [
                        'GET payment-detail/<id>' => 'payment-detail',
                        'GET <id>' => 'list-all-restaurants-payment-method',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS payment-detail/<id>' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order',
                    'pluralize' => false,
                    'patterns' => [
                        'POST <id>' => 'place-an-order',
                        'GET callback' => 'callback',
                        'GET <id>/<restaurant_uuid>' => 'order-details',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS callback' => 'options',
                        'OPTIONS <id>/<restaurant_uuid>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
