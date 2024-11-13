<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],//'debug'
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [
            'basePath' => '@api/modules/v2',
            'class' => 'api\modules\v2\Module',
        ],
        /*'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['*']//'1.2.3.4', '127.0.0.1', '::1'
        ]*/
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\models\Customer',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'eventManager' => [
            'class' => 'api\components\EventManager',
        ],
        'request' => [
            'enableCookieValidation' => false,
            // Accept and parse JSON Requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'currency' => [
            'class' => 'api\components\Currency',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [ // PingController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/ping',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'test',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // BlogController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/blog',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// CampaignController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/campaign',
                    'patterns' => [
                        'PATCH click/<id>' => 'click',
                        // OPTIONS VERBS
                        'OPTIONS click/s<id>' => 'options',
                    ]
                ],
                [ // AccountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/account',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        'PATCH' => 'update',
                        'DELETE' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // AddressController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/address',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'detail',
                        'POST' => 'add',
                        'PATCH <id>' => 'update',
                        'DELETE <id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'GET locate' => 'locate',
                        'POST signup' => 'signup',
                        'POST register' => 'signup',
                        'PATCH update-password' => 'update-password',
                        'POST request-reset-password' => 'request-reset-password',
                        'POST is-email-verified' => 'is-email-verified',
                        'POST update-email' => 'update-email',
                        'POST resend-verification-email' => 'resend-verification-email',
                        'POST verify-email' => 'verify-email',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS locate' => 'options',
                        'OPTIONS update-password' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS register' => 'options',
                        'OPTIONS signup' => 'options',
                        'OPTIONS is-email-verified' => 'options',
                        'OPTIONS update-email' => 'options',
                        'OPTIONS resend-verification-email' => 'options',
                        'OPTIONS verify-email' => 'options',
                    ]
                ],
                [// ItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/item',
                    'pluralize' => false,
                    'patterns' => [
                        'GET detail' => 'item-data',
                        'GET' => 'restaurant-menu',
                        'GET category-items' => 'category-items',
                        'GET <category_id>' => 'category-products',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS category-items' => 'options',
                        'OPTIONS <category_id>' => 'options',
                    ]
                ],
                [ // SitemapController
                  'class' => 'yii\rest\UrlRule',
                  'controller' => 'v2/sitemap',
                  'pluralize' => false,
                  'patterns' => [
                      'GET <storeUuid>' => 'index',
                      // OPTIONS VERBS
                      'OPTIONS' => 'options',
                      'OPTIONS <storeUuid>' => 'options',
                  ]
                ],
                [// ItemController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/item',
                    'pluralize' => false,
                    'patterns' => [
                        'GET view/<slug>' => 'view',
                        'GET detail' => 'item-data',
                        'GET items' => 'items',
                        'GET category-items' => 'category-items',
                        'GET' => 'restaurant-menu',
                        'GET category/<slug>' => 'category-products',
                        'GET <category_id>' => 'category-products',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS items' => 'options',
                        'OPTIONS category-items' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS view/<slug>' => 'options',
                        'OPTIONS category/<slug>' => 'options',
                        'OPTIONS <category_id>' => 'options',
                    ]
                ],
                [// DeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET by-location' => 'by-location',
                        'GET country-states/<country_id>' => 'country-states',
                        'GET country-cities/<country_id>' => 'country-cities',
                        'GET state-cities/<state_id>' => 'state-cities',
                        'GET city-areas/<city_id>' => 'city-areas',
                        'GET areas/<country_id>' => 'country-areas',
                        'GET cities/<state_id>' => 'cities',
                        'GET states/<country_id>' => 'states',
                        'GET countries' => 'countries',
                        'GET list-of-countries/<restaurant_uuid>' => 'list-of-countries',
                        'GET list-pickup-locations/<restaurant_uuid>' => 'list-pickup-locations',
                        'GET list-of-areas/<restaurant_uuid>/<country_id>' => 'list-of-areas',
                        'GET pickup-location/<restaurant_uuid>/<pickup_location_id>' => 'get-pickup-location',
                        'GET <restaurant_uuid>/<delivery_zone_id>' => 'get-delivery-zone',
                        // 'GET <restaurant_uuid>' => 'delivery-zone',
                        'POST get-delivery-time' => 'get-delivery-time',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS city-areas/<city_id>' => 'options',
                        'OPTIONS by-location' => 'options',
                        'OPTIONS country-cities/<country_id>' => 'options',
                        'OPTIONS country-states/<country_id>' => 'options',
                        'OPTIONS state-cities/<state_id>' => 'options',
                        'OPTIONS cities' => 'options',
                        'OPTIONS states' => 'options',
                        'OPTIONS countries' => 'options',
                        'OPTIONS list-of-countries/<restaurant_uuid>' => 'options',
                        'OPTIONS list-pickup-locations/<restaurant_uuid>' => 'options',
                        'OPTIONS list-of-areas/<restaurant_uuid>/<country_id>' => 'options',
                        'OPTIONS pickup-location/<restaurant_uuid>/<pickup_location_id>' => 'options',
                        'OPTIONS <restaurant_uuid>/<delivery_zone_id>' => 'options',
                        // 'OPTIONS <restaurant_uuid>' => 'options'
                        'OPTIONS get-delivery-time' => 'options',
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
                [// StoreController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/store',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET get-opening-hours' => 'get-opening-hours',
                        'GET locations/<id>' => 'list-all-stores-locations',
                        'GET get-restaurant-data/<branch_name>' => 'get-restaurant-data',
                        'GET by-domain/<domain>' => 'by-domain',
                        'GET by-package/<id>' => 'by-package',
                        'GET manifest-by-domain/<domain>' => 'manifest-by-domain',
                        'GET <id>' => 'view',
                        'POST get-delivery-time' => 'get-delivery-time',
                        // OPTIONS VERBS
                        'OPTIONS get-opening-hours' => 'options',
                        'OPTIONS get-delivery-time' => 'options',
                        'OPTIONS locations/<id>' => 'options',
                        'OPTIONS get-restaurant-data/<branch_name>' => 'options',
                        'OPTIONS by-domain/<domain>' => 'options',
                        'OPTIONS by-package/<id>' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS manifest-by-domain/<domain>' => 'options',
                        'OPTIONS' => 'options',
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
                        'GET' => 'list-all-restaurants-payment-method',
                        'GET <id>' => 'list-all-restaurants-payment-method',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// ApplePayController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment/apple-pay',
                    'pluralize' => false,
                    'patterns' => [
                        'POST process-payment' => 'process-payment',
                        'PATCH process-payment' => 'process-payment',
                        'POST validate-merchant' => 'validate-merchant',
                        // OPTIONS VERBS
                        'OPTIONS process-payment' => 'options',
                        'OPTIONS validate-merchant' => 'options',
                    ]
                ],
                [// MoyasarController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment/moyasar',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS callback' => 'options',
                    ]
                ],
                [// UpaymentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment/upayment',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS callback' => 'options',
                    ]
                ],
                [// TabbyController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment/tabby',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'GET create' => 'create',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        'GET confirm' => 'confirm',
                        'POST confirm' => 'confirm',
                        "PATCH capture" => "capture",
                       // "PATCH refund" => "refund",
                       // "PATCH close" => "close",
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        "OPTIONS create" => 'options',
                        'OPTIONS callback' => 'options',
                        'OPTIONS confirm' => 'options',
                        'OPTIONS capture' => 'options',
                        'OPTIONS refund' => 'options',
                        'OPTIONS close' => 'options',
                    ]
                ],
                [// StripeController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment/stripe',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'GET client-secret' => 'client-secret',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS client-secret' => 'options',
                        'OPTIONS callback' => 'options',
                    ]
                ],
                [// PaymentMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/payment',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list-all-restaurants-payment-method',
                        'GET <id>' => 'list-all-restaurants-payment-method',
                        'POST status-update-webhook' => 'my-fatoorah-webhook',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS status-update-webhook' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/order',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET download-invoice/<id>' => "download-invoice",
                        "POST validate-cart" => "validate-cart",
                        'POST status-update-webhook' => 'update-mashkor-order-status',
                        'POST init-order/<id>' => 'init-order',
                        'POST apply-promo-code/<order_uuid>' => 'apply-promo-code',
                        'POST instruction/<order_uuid>' => 'instruction',
                        'POST <id>' => 'place-an-order',
                        'GET check-for-pending-orders/<restaurant_uuid>' => 'check-pending-orders',
                        'GET callback' => 'callback',
                        'GET apply-promo-code' => 'apply-promo-code',
                        'GET apply-bank-discount' => 'apply-bank-discount',
                        'GET order-details/<id>/<restaurant_uuid>' => 'order-details',
                        'GET <id>/<restaurant_uuid>' => 'order-details',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        "OPTIONS validate-cart" => 'options',
                        'OPTIONS download-invoice/<id>' => 'options',
                        'OPTIONS status-update-webhook' => 'options',
                        'OPTIONS init-order/<id>' => 'options',
                        'OPTIONS instruction/<order_uuid>' => 'options',
                        'OPTIONS apply-promo-code/<order_uuid>' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS check-for-pending-orders/<restaurant_uuid>' => 'options',
                        'OPTIONS callback' => 'options',
                        'OPTIONS apply-promo-code' => 'options',
                        'OPTIONS apply-bank-discount' => 'options',
                        'OPTIONS <id>/<restaurant_uuid>' => 'options',
                        'OPTIONS order-details/<id>/<restaurant_uuid>' => 'options',
                    ]
                ],
                [// PageController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/page',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <slug>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <slug>' => 'options',
                    ]
                ],
                [// VoucherController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/voucher',
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [// OrderController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/order',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET download-invoice/<id>' => "download-invoice",
                        "POST validate-cart" => "validate-cart",
                        'POST payment-webhook' => 'payment-webhook',
                        'POST status-update-webhook' => 'update-mashkor-order-status',
                        'POST update-armada-order-status' => 'update-armada-order-status',
                        'POST init-order/<id>' => 'init-order',
                        'POST apply-promo-code/<order_uuid>' => 'apply-promo-code',
                        'POST instruction/<order_uuid>' => 'instruction',
                        'POST <id>' => 'place-an-order',
                        'PATCH <id>' => 'update-order',
                        'GET check-for-pending-orders/<restaurant_uuid>' => 'check-pending-orders',
                        'GET callback' => 'callback',
                        'GET my-fatoorah-callback' => 'my-fatoorah-callback',
                        'GET apply-promo-code' => 'apply-promo-code',                        
                        'GET apply-bank-discount' => 'apply-bank-discount',
                        'GET order-details/<id>/<restaurant_uuid>' => 'order-details',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        "OPTIONS validate-cart" => "options",
                        'OPTIONS download-invoice/<id>' => 'options',
                        'OPTIONS payment-webhook' => 'options',
                        'OPTIONS status-update-webhook' => 'options',
                        'OPTIONS update-armada-order-status' => 'options',
                        'OPTIONS init-order/<id>' => 'options',
                        'OPTIONS instruction/<order_uuid>' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS check-for-pending-orders/<restaurant_uuid>' => 'options',
                        'OPTIONS callback' => 'options',
                        'OPTIONS my-fatoorah-callback' => 'options',
                        'OPTIONS apply-promo-code/<order_uuid>' => 'options',
                        'OPTIONS apply-promo-code' => 'options',
                        'OPTIONS apply-bank-discount' => 'options',
                        'OPTIONS order-details/<id>/<restaurant_uuid>' => 'options',
                    ]
                ],
                [//ZapierController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/zapier',
                    'pluralize' => false,
                    'patterns' => [
                        'GET get-store-list' => 'get-store-list',
                        'GET get-latest-order/<restaurant_uuid>' => 'get-latest-order',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS get-store-list' => 'options',
                        'OPTIONS get-latest-order/<restaurant_uuid>' => 'options',
                    ]
                ],
                [//ZapierController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/zapier',
                    'pluralize' => false,
                    'patterns' => [
                        'GET get-store-list' => 'get-store-list',
                        'GET get-latest-order/<restaurant_uuid>' => 'get-latest-order',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS get-store-list' => 'options',
                        'OPTIONS get-latest-order/<restaurant_uuid>' => 'options',
                    ]
                ],
                [//ChatController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/chat',
                    'pluralize' => false,
                    'patterns' => [
                        'POST <id>' => 'chat',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options'
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
