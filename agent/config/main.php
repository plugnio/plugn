<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [

                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order',
                    'pluralize' => false,
                    'patterns' => [
                        'GET detail' => 'detail',
                        'GET orders-report' => 'orders-report',
                        'GET total-active' => 'total-active',
                        'GET <type>' => 'list',
                        'POST <store_uuid>' => 'place-an-order',
                        'PATCH <order_uuid>/<store_uuid>' => 'update',
                        'PATCH update-order-status/<order_uuid>/<store_uuid>' => 'update-order-status',
                        'POST request-driver-from-armada/<order_uuid>/<store_uuid>' => 'request-driver-from-armada',
                        'POST request-driver-from-mashkor/<order_uuid>/<store_uuid>' => 'request-driver-from-mashkor',
                        'POST create/<store_uuid>' => 'create',
                        'DELETE <order_uuid>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS total-active' => 'options',
                        'OPTIONS orders-report' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS update-order-status/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS request-driver-from-armada/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS request-driver-from-mashkor/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS <store_uuid>' => 'options',
                        'OPTIONS <order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS <type>' => 'options',
                        'OPTIONS create/<store_uuid>' => 'options',
                    ]
                ],
                [// OrderItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order-item',
                    'pluralize' => false,
                    'patterns' => [
                        'PATCH' => 'update',
                        'DELETE' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [// CategoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/category',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'POST upload-image' => 'upload-category-image',
                        'PATCH <category_id>/<store_uuid>' => 'update',
                        'DELETE <category_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <category_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// StaffController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/staff',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <assignment_id>/<store_uuid>' => 'update',
                        'DELETE <assignment_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <agent_assignment_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// bankController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bank',
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
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// CityController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/city',
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// AreaController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/area',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options'
                    ]
                ],
                [// AgentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/agent',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        'GET store-profile' => 'store-profile',
                        'GET stores' => 'stores',
                        'PUT update' => 'update-agent-profile',
                        'POST change-password' => 'change-password',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS store-profile' => 'options',
                        'OPTIONS stores' => 'options',
                        'OPTIONS update' => 'options',
                        'OPTIONS change-password' => 'options',
                    ]
                ],
                [// VoucherController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/voucher',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <voucher_id>/<store_uuid>' => 'update',
                        'PATCH update-status' => 'update-voucher-status',
                        'DELETE <voucher_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS update-status' => 'options',
                        'OPTIONS <voucher_id>/<store_uuid>' => 'options',
                        'OPTIONS create' => 'options'
                    ]
                ],
                [// BankDiscountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bank-discount',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <bank_discount_id>/<store_uuid>' => 'update',
                        'PATCH update-status' => 'update-bank-discount-status',
                        'DELETE <bank_discount_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS update-status' => 'options',
                        'OPTIONS <bank_discount_id>/<store_uuid>' => 'options',
                        'OPTIONS create' => 'options'
                    ]
                ],
                [// DeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'DELETE <delivery_zone_id>/<store_uuid>' => 'delete',
                        'PATCH <delivery_zone_id>/<store_uuid>' => 'update',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <delivery_zone_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// OpeningHoursController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/opening-hours',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET <day_of_week>/<store_uuid>' => 'detail',
                        'POST <store_uuid>' => 'create',
                        'PATCH <day_of_week>' => 'update',
                        'DELETE <opening_hour_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <day_of_week>' => 'options',
                        'OPTIONS <opening_hour_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// AreaDeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/area-delivery-zone',
                    'patterns' => [
                        'GET' => 'list',
                        'PATCH save' => 'save',
                        'POST create' => 'create',
                        'DELETE <area_delivery_zone_id>/<store_uuid>' => 'delete',
                        'PATCH <area_delivery_zone_id>/<store_uuid>' => 'update',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS save' => 'options',
                        'OPTIONS <area_delivery_zone_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// WebLinkController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/web-link',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <web_link_id>/<store_uuid>' => 'update',
                        'DELETE <web_link_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS <web_link_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// ItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/item',
                    'patterns' => [
                        'GET' => 'list',
                        'GET export-to-excel' => 'export-to-excel',
                        'GET items-report' => 'items-report',
                        'GET <id>' => 'detail',
                        'POST' => 'create',
                        'POST update-stock' => 'update-stock-qty',
                        'PATCH <id>' => 'update',
                        'PATCH update-status/<id>/<store_uuid>' => 'change-status',
                        'DELETE <id>' => 'delete',
                        'DELETE delete-image/<id>/<image>' => 'delete-image',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS export-to-excel' => 'options',
                        'OPTIONS items-report' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS update-stock' => 'options',
                        'OPTIONS update-status/<id>/<store_uuid>' => 'options',
                        'OPTIONS delete-image/<id>/<image>' => 'options',
                    ]
                ],
                [// CustomerController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/customer',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET export-to-excel' => 'export-to-excel',
                        'GET detail' => 'detail',
                        'GET orders' => 'list-all-customer-orders',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS export-to-excel' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS orders' => 'options'
                    ]
                ],
                [// BusinessLocationController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/business-location',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <business_location_id>/<store_uuid>' => 'update',
                        'DELETE <business_location_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <business_location_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// StoreController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/store',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        'POST' => 'update',
                        'POST connect-domain' => 'connect-domain',
                        'POST disable-payment-method/<id>/<paymentMethodId>' => 'disable-payment-method',
                        'POST enable-payment-method/<id>/<paymentMethodId>' => 'enable-payment-method',
                        'GET view-payment-methods/<id>' => 'view-payment-methods',
                        'POST create-tap-account/<id>' => 'create-tap-account',
                        'POST enable-online-payment/<id>' => 'enable-online-payment',
                        'POST disable-online-payment/<id>' => 'disable-online-payment',
                        'POST enable-cod/<id>' => 'enable-cod',
                        'POST disable-cod/<id>' => 'disable-cod',
                        'POST update-layout' => 'update-layout',
                        'POST update-analytics-integration/<id>' => 'update-analytics-integration',
                        'POST update-delivery-integration/<id>' => 'update-delivery-integration',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS connect-domain' => 'options',
                        'OPTIONS update-delivery-integration/<id>' => 'options',
                        'OPTIONS update-analytics-integration/<id>' => 'options',
                        'OPTIONS disable-payment-method/<id>/<paymentMethodId>' => 'options',
                        'OPTIONS enable-payment-method/<id>/<paymentMethodId>' => 'options',
                        'OPTIONS view-payment-methods/<id>' => 'options',
                        'OPTIONS create-tap-account/<id>' => 'options',
                        'OPTIONS enable-online-payment/<id>' => 'options',
                        'OPTIONS disable-online-payment/<id>' => 'options',
                        'OPTIONS enable-cod/<id>' => 'options',
                        'OPTIONS disable-cod/<id>' => 'options',
                        'OPTIONS update-layout' => 'options'
                    ]
                ],

                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'PATCH update-password' => 'update-password',
                        'POST request-reset-password' => 'request-reset-password',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS update-password' => 'options',
                        'OPTIONS request-reset-password' => 'options'
                    ]
                ],
                [ // StatsController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/stats',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [ // PlanController
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'v1/plan',
                   'patterns' => [
                       'GET callback' => 'callback',
                       'GET <id>' => 'view',
                       'POST confirm' => 'confirm',
                       // OPTIONS VERBS
                       'OPTIONS <id>' => 'options'
                   ]
               ],
            ],
        ],
    ],
    'params' => $params,
];
