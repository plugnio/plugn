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
                        'GET stats' => 'stats',
                        'GET orders-report' => 'orders-report',
                        'GET total-pending' => 'total-pending',
                        'GET download-invoice/<id>' => 'download-invoice',
                        'GET archive-orders' => 'archive-orders',
                        'GET live-orders' => 'live-orders',
                        'GET <type>' => 'list',
                        'GET' => 'list',
                        'POST <store_uuid>' => 'place-an-order',
                        'POST' => 'place-an-order',
                        'POST request-driver-from-armada/<order_uuid>/<store_uuid>' => 'request-driver-from-armada',
                        'POST request-driver-from-mashkor/<order_uuid>/<store_uuid>' => 'request-driver-from-mashkor',
                        'POST request-payment-status-from-tap/<order_uuid>/<store_uuid>' => 'request-payment-status-from-tap',
                        'POST create/<store_uuid>' => 'create',
                        'POST create' => 'create',
                        'PATCH update-order-status/<order_uuid>/<store_uuid>' => 'update-order-status',
                        'PATCH refund/<order_uuid>' => 'refund',
                        'PATCH update-order-status/<order_uuid>' => 'update-order-status',
                        'PATCH <order_uuid>' => 'update',
                        'PATCH <order_uuid>/<store_uuid>' => 'update',
                        'DELETE soft-delete/<order_uuid>/<store_uuid>' => 'soft-delete',
                        'DELETE soft-delete/<order_uuid>' => 'soft-delete',
                        'DELETE <order_uuid>/<store_uuid>' => 'delete',
                        'DELETE <order_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS stats' => 'options',
                        'OPTIONS total-active' => 'options',
                        'OPTIONS orders-report' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS download-invoice/<id>' => 'options',
                        'OPTIONS refund/<order_uuid>' => 'options',
                        'OPTIONS update-order-status/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS request-driver-from-armada/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS request-driver-from-mashkor/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS update-order-status/<order_uuid>' => 'options',
                        'OPTIONS request-driver-from-armada/<order_uuid>' => 'options',
                        'OPTIONS request-driver-from-mashkor/<order_uuid>' => 'options',
                        'OPTIONS <store_uuid>' => 'options',
                        'OPTIONS <order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS <order_uuid>' => 'options',
                        'OPTIONS <type>' => 'options',
                        'OPTIONS archive-orders' => 'options',
                        'OPTIONS live-orders' => 'options',
                        'OPTIONS create/<store_uuid>' => 'options',
                        'OPTIONS soft-delete/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS soft-delete/<order_uuid>' => 'options',
                        'OPTIONS request-payment-status-from-tap/<order_uuid>/<store_uuid>' => 'options',
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
                [// AreaDeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/area-delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'POST create' => 'create',
                        'PATCH save' => 'save-details',
                        'PATCH <area_delivery_zone_id>/<store_uuid>' => 'update',
                        'DELETE <area_delivery_zone_id>/<store_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS save' => 'options',
                        'OPTIONS <area_delivery_zone_id>/<store_uuid>' => 'options',
                    ]
                ],
                [// CategoryController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/category',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'GET item-list' => 'item-list',
                        'POST create' => 'create',
                        'POST upload-image' => 'upload-category-image',
                        'POST update-position' => 'change-position',
                        'PATCH <category_id>/<store_uuid>' => 'update',
                        'DELETE <category_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <category_id>' => 'options',
                        'OPTIONS <category_id>/<store_uuid>' => 'options',
                        'OPTIONS update-position' => 'options',
                        'OPTIONS item-list' => 'options',
                    ]
                ],
                [ // SitemapController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/sitemap',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
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
                [// BankController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bank',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options'
                    ]
                ],
                [// CurrencyController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/currency',
                    'patterns' => [
                        'GET' => 'list',
                        'GET store-currencies' => 'store-currencies',
                        'POST' => 'update',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS store-currencies' => 'options'
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
                        //todo: update-email

                        'GET store-profile' => 'store-profile',
                        'GET stores' => 'stores',
                        'GET language-pref' => 'language-pref',
                        'PATCH language-pref' => 'language-pref',
                        'PUT update' => 'update-agent-profile',
                        'POST change-password' => 'change-password',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS store-profile' => 'options',
                        'OPTIONS stores' => 'options',
                        'OPTIONS update' => 'options',
                        'OPTIONS change-password' => 'options',
                        'OPTIONS language-pref' => 'options'
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
                        'DELETE <voucher_id>/<store_uuid>' => 'remove',
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
                        'PATCH <delivery_zone_id>/<store_uuid>' => 'update',
                        'DELETE <delivery_zone_id>/<store_uuid>' => 'delete',
                        'DELETE cancel-override/<delivery_zone_id>/<store_uuid>' => 'cancel-override',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <delivery_zone_id>/<store_uuid>' => 'options',
                        'OPTIONS cancel-override/<delivery_zone_id>/<store_uuid>' => 'options',
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
                        'POST update-position' => 'change-position',
                        'PATCH <id>' => 'update',
                        'PATCH update-status/<id>/<store_uuid>' => 'change-status',
                        'DELETE <id>' => 'delete',
                        'DELETE delete-image/<id>/<image>' => 'delete-image',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS export-to-excel' => 'options',
                        'OPTIONS items-report' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS update-position' => 'options',
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
                        'POST' => 'create',
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
                        'GET status' => 'status',
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
                        'POST enable-free-checkout/<id>' => 'enable-free-checkout',
                        'POST disable-free-checkout/<id>' => 'disable-free-checkout',
                        'POST update-layout' => 'update-layout',
                        'POST update-analytics-integration/<id>' => 'update-analytics-integration',
                        'POST update-delivery-integration/<id>' => 'update-delivery-integration',
                        'PATCH update-status/<id>/<status>' => 'update-store-status',
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
                        'OPTIONS enable-free-checkout/<id>' => 'options',
                        'OPTIONS disable-free-checkout/<id>' => 'options',
                        'OPTIONS update-layout' => 'options',
                        'OPTIONS update-status/<id>/<status>' => 'options',
                        'OPTIONS status' => 'options'
                    ]
                ],

                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'POST signup' => 'signup',
                        'PATCH update-password' => 'update-password',
                        'POST request-reset-password' => 'request-reset-password',
                        'POST is-email-verified' => 'is-email-verified',
                        'POST update-email' => 'update-email',
                        'POST resend-verification-email' => 'resend-verification-email',
                        'POST verify-email' => 'verify-email',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS update-password' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS signup' => 'options',
                        'OPTIONS is-email-verified' => 'options',
                        'OPTIONS update-email' => 'options',
                        'OPTIONS resend-verification-email' => 'options',
                        'OPTIONS verify-email' => 'options'
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
                [ // RestaurantPaymentMethod
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'v1/restaurant-payment-method',
                   'patterns' => [
                       'GET' => 'index',
                       'GET all' => 'list-all',
                       // OPTIONS VERBS
                       'OPTIONS' => 'options',
                       'OPTIONS all' => 'options'
                   ]
               ],
            ],
        ],
    ],
    'params' => $params,
];
