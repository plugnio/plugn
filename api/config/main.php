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
                        'GET detail' => 'item-data',
                        'GET' => 'restaurant-menu',
                        'POST delete-item-image' => 'delete-item-image',
                        'GET <category_id>' => 'category-products',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS delete-item-image' => 'options',
                        'OPTIONS <category_id>' => 'options',
                    ]
                ],
                [// ZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET list-of-countries/<restaurant_uuid>' => 'list-of-countries',
                        'GET <restaurant_uuid>/<country_id>' => 'list-of-areas',
                        'GET <restaurant_uuid>' => 'delivery-zone',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS list-of-countries/<restaurant_uuid>' => 'options',
                        'OPTIONS <restaurant_uuid>/<country_id>' => 'options',
                        'OPTIONS <restaurant_uuid>' => 'options'
                    ]
                ],
                [// RestaurantController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant',
                    'pluralize' => false,
                    'patterns' => [
                        'GET get-opening-hours' => 'get-opening-hours',
                        'GET branches/<id>' => 'list-all-restaurants-branches',
                        'GET get-restaurant-data/<branch_name>' => 'get-restaurant-data',
                        // OPTIONS VERBS
                        'OPTIONS get-opening-hours' => 'options',
                        'OPTIONS branches/<id>' => 'options',
                        'OPTIONS get-restaurant-data/<branch_name>' => 'options',
                    ]
                ],
                [// RestaurantDeliveryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant-delivery',
                    'pluralize' => false,
                    'patterns' => [
                        'GET delivery-area/<restaurant_uuid>' => 'list-all-cities',
                        'GET <id>/<restaurant_uuid>' => 'delivered-area-data',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS delivery-area/<restaurant_uuid>' => 'options',
                        'OPTIONS <id>/<restaurant_uuid>' => 'options',
                    ]
                ],
                [// PaymentMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment',
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
                        'POST status-update-webhook' => 'update-mashkor-order-status',
                        'POST <id>' => 'place-an-order',
                        'GET check-for-pending-orders/<restaurant_uuid>' => 'check-pending-orders',
                        'GET callback' => 'callback',
                        'GET apply-promo-code' => 'apply-promo-code',
                        'GET apply-bank-discount' => 'apply-bank-discount',
                        'GET <id>/<restaurant_uuid>' => 'order-details',
                        'GET order-details/<id>/<restaurant_uuid>' => 'get-order-details',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS status-update-webhook' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS check-for-pending-orders/<restaurant_uuid>' => 'options',
                        'OPTIONS callback' => 'options',
                        'OPTIONS apply-promo-code' => 'options',
                        'OPTIONS apply-bank-discount' => 'options',
                        'OPTIONS <id>/<restaurant_uuid>' => 'options',
                        'OPTIONS order-details/<id>/<restaurant_uuid>' => 'options',
                    ]
                ],
                [//ZapierController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/zapier',
                    'pluralize' => false,
                    'patterns' => [
                        'GET get-latest-order/<restaurant_uuid>' => 'get-latest-order',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS get-latest-order/<restaurant_uuid>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
