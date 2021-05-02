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
                        'GET' => 'list-pending-orders',
                        'GET active' => 'list-active-orders',
                        'GET draft' => 'list-draft-orders',
                        'GET abandoned' => 'list-abandoned-orders',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS active' => 'options',
                        'OPTIONS draft' => 'options',
                        'OPTIONS abandoned' => 'options',
                        'OPTIONS detail' => 'options',
                    ]
                ],
                [// CategoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/category',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [// CountryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/country',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [// AgentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/agent',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [// VoucherController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/voucher',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// BankDiscountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bank-discount',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// DeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// WebLinkController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/web-link',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// ItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/item',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// CustomerController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/customer',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// BusinessLocationController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/business-location',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// StoreController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/store',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
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
