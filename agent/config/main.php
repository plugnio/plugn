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
        'eventManager' => [
            'class' => 'agent\components\EventManager',
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

                [ // TabbyController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment/tabby',
                    'pluralize' => false,
                    'patterns' => [
                        "GET <id>" => "order",
                        "POST install" => "install",
                        "PATCH refund" => "refund",
                        "PATCH close" => "close",
                        // OPTIONS VERBS
                        'OPTIONS install' => 'options',
                        'OPTIONS refund' => 'options',
                        'OPTIONS close' => 'options',
                    ]
                ],

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
                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'GET locate' => 'locate',
                        'POST signup-step-one' => 'signup-step-one',
                        'POST login-auth0' => 'login-auth0',
                        'POST signup' => 'signup',
                        'PATCH update-password' => 'update-password',
                        'POST request-reset-password' => 'request-reset-password',
                        'POST is-email-verified' => 'is-email-verified',
                        'POST update-email' => 'update-email',
                        'POST resend-verification-email' => 'resend-verification-email',
                        'POST verify-email' => 'verify-email',
                        'POST login-by-apple' => 'login-by-apple',
                        'POST login-by-google' => 'login-by-google',
                        'POST login-by-key' => 'login-by-key',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS locate' => 'options',
                        'OPTIONS login-auth0' => 'options',
                        'OPTIONS update-password' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS signup' => 'options',
                        'OPTIONS is-email-verified' => 'options',
                        'OPTIONS update-email' => 'options',
                        'OPTIONS resend-verification-email' => 'options',
                        'OPTIONS verify-email' => 'options',
                        'OPTIONS signup-step-one' => 'options',
                        'OPTIONS login-by-key' => 'options',
                        'OPTIONS login-by-apple' => 'options',
                        'OPTIONS login-by-google' => 'options'
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
                        'DELETE' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS store-profile' => 'options',
                        'OPTIONS stores' => 'options',
                        'OPTIONS update' => 'options',
                        'OPTIONS change-password' => 'options',
                        'OPTIONS language-pref' => 'options'
                    ]
                ],
                [ // BlogController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/blog',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'view',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
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

                [// BankDiscountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/bank-discount',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST' => 'create',
                        'POST create' => 'create',
                        'PATCH update-status' => 'update-bank-discount-status',
                        'PATCH <bank_discount_id>/<store_uuid>' => 'update',
                        'PATCH <bank_discount_id>' => 'update',
                        'DELETE <bank_discount_id>/<store_uuid>' => 'delete',
                        'DELETE <bank_discount_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS update-status' => 'options',
                        'OPTIONS <bank_discount_id>/<store_uuid>' => 'options',
                        'OPTIONS <bank_discount_id>' => 'options',
                        'OPTIONS create' => 'options'
                    ]
                ],

                [// BusinessLocationController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/business-location',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST' => 'create',
                        'POST create' => 'create',
                        'PATCH <business_location_id>/<store_uuid>' => 'update',
                        'PATCH <business_location_id>' => 'update',
                        'DELETE <business_location_id>/<store_uuid>' => 'delete',
                        'DELETE <business_location_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <business_location_id>' => 'options',
                        'OPTIONS <business_location_id>/<store_uuid>' => 'options',
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
                        'POST' => 'create',
                        'POST create' => 'create',
                        'POST upload-image' => 'upload-category-image',
                        'POST update-position' => 'change-position',
                        'PATCH <category_id>/<store_uuid>' => 'update',
                        'PATCH <category_id>' => 'update',
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
                [// StateController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/state',
                    'patterns' => [
                        'GET' => 'list',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
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
                        'GET download-today-invoices' => 'download-today-invoices',
                        'GET archive-orders' => 'archive-orders',
                        'GET live-orders' => 'live-orders',
                        'GET <type>' => 'list',
                        'GET' => 'list',
                        'POST' => 'place-an-order',
                        'POST schedule-pickup-aramex/<order_uuid>' => 'schedule-pickup-aramex',
                        'POST cancel-delivery/<id>' => 'cancel-delivery',
                        'POST create-shipment-aramex/<order_uuid>' => 'create-shipment-aramex',
                        'POST request-driver-from-armada/<order_uuid>/<store_uuid>' => 'request-driver-from-armada',
                        'POST request-driver-from-mashkor/<order_uuid>/<store_uuid>' => 'request-driver-from-mashkor',
                        'POST request-payment-status-from-tap/<order_uuid>/<store_uuid>' => 'request-payment-status-from-tap',
                        'POST request-driver-from-armada/<order_uuid>' => 'request-driver-from-armada',
                        'POST request-driver-from-mashkor/<order_uuid>' => 'request-driver-from-mashkor',
                        'POST request-payment-status-from-tap/<order_uuid>' => 'request-payment-status-from-tap',
                        'POST create/<store_uuid>' => 'create',
                        'POST create' => 'create',
                        'POST <store_uuid>' => 'create',
                        'PATCH update-order-status/<order_uuid>/<store_uuid>' => 'update-order-status',
                        'PATCH update-order-status/<order_uuid>' => 'update-order-status',
                        'PATCH refund/<order_uuid>' => 'refund',
                        'PATCH <order_uuid>/<store_uuid>' => 'update',
                        'PATCH <order_uuid>' => 'update',
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
                        'OPTIONS create-shipment-aramex/<order_uuid>' => 'options',
                        'OPTIONS schedule-pickup-aramex' => 'options',
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
                        'OPTIONS cancel-delivery/<id>' => 'options',
                        'OPTIONS soft-delete/<order_uuid>' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS request-payment-status-from-tap/<order_uuid>/<store_uuid>' => 'options',
                        'OPTIONS request-payment-status-from-tap/<order_uuid>' => 'options',
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
                [
                    //PlugnUpdateController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/plugn-update',
                    'patterns' => [
                        'GET' => 'index',
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
                        'PATCH save-cities' => 'save-cities',
                        'PATCH <area_delivery_zone_id>/<store_uuid>' => 'update',
                        'PATCH <area_delivery_zone_id>' => 'update',
                        'DELETE <area_delivery_zone_id>/<store_uuid>' => 'delete',
                        'DELETE <area_delivery_zone_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS save-cities' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS save' => 'options',
                        'OPTIONS <area_delivery_zone_id>/<store_uuid>' => 'options',
                        'OPTIONS <area_delivery_zone_id>' => 'options',
                    ]
                ],
                [// CampaignController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/campaign',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'detail',
                        'POST' => 'create',
                        'PATCH <id>' => 'update',
                        'PATCH click/<id>' => 'click',
                        'DELETE delete/<id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS click/s<id>' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS delete/<id>' => 'options',
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
                [// AddonController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/addon',
                    'patterns' => [
                        'GET' => 'list',
                        'GET callback' => 'callback',
                        //'GET payment-webhook' => 'payment-webhook',
                        'GET <id>' => 'detail',
                        'POST confirm' => 'confirm',
                        'POST payment-webhook' => 'payment-webhook',
                        'PATCH payment-webhook' => 'payment-webhook',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options'
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
                        'PATCH <assignment_id>' => 'update',
                        'DELETE <assignment_id>/<store_uuid>' => 'delete',
                        'DELETE <assignment_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS <agent_assignment_id>/<store_uuid>' => 'options',
                        'OPTIONS <agent_assignment_id>' => 'options',
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
                        'PATCH comment/<ticket_uuid>' => 'comment',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS comment/<ticket_uuid>' => 'options',
                        'OPTIONS comments/<id>' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// AreaController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/area',
                    'patterns' => [
                        'GET' => 'list',
                        'GET delivery-areas' => 'delivery-areas',
                        'GET <id>' => 'detail',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options'
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
                        'PATCH update-status' => 'update-voucher-status',
                        'PATCH <voucher_id>/<store_uuid>' => 'update',
                        'PATCH <voucher_id>' => 'update',
                        'DELETE <voucher_id>/<store_uuid>' => 'remove',
                        'DELETE <voucher_id>' => 'remove',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS update-status' => 'options',
                        'OPTIONS <voucher_id>/<store_uuid>' => 'options',
                        'OPTIONS <voucher_id>' => 'options',
                        'OPTIONS create' => 'options'
                    ]
                ],
                [// SupplierController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/supplier',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'detail',
                        'POST' => 'create',
                        'PATCH <id>' => 'update',
                        'DELETE <id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// IngredientController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/ingredient',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <id>' => 'detail',
                        'POST' => 'create',
                        'PATCH <id>' => 'update',
                        'DELETE <id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// DeliveryZoneController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/delivery-zone',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'GET cities/<state_id>' => 'cities',
                        'GET states/<country_id>' => 'states',
                        'GET areas/<city_id>' => 'areas',
                        'GET list-of-countries/<restaurant_uuid>' => 'list-of-countries',
                        'GET list-of-areas/<restaurant_uuid>/<country_id>' => 'list-of-areas',
                        'GET detail-by-location' => 'detail-by-location',
                        'POST create' => 'create',
                        'POST add-state-to-delivery-area' => "add-state-to-delivery-area",
                        'PATCH <delivery_zone_id>/<store_uuid>' => 'update',
                        'PATCH <delivery_zone_id>' => 'update',
                        "DELETE remove-state-from-delivery-area/<state_id>/<delivery_zone_id>" => "remove-state-from-delivery-area",
                        'DELETE cancel-override/<delivery_zone_id>/<store_uuid>' => 'cancel-override',
                        'DELETE cancel-override/<delivery_zone_id>' => 'cancel-override',
                        'DELETE <delivery_zone_id>/<store_uuid>' => 'delete',
                        'DELETE <delivery_zone_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS add-state-to-delivery-area' =>  'options',
                        "OPTIONS remove-state-from-delivery-area/<state_id>/<delivery_zone_id>" => "options",

                        'OPTIONS detail-by-location' => 'options',
                        'OPTIONS cities/<state_id>' => 'options',
                        'OPTIONS states/<country_id>' => 'options',
                        'OPTIONS areas/<city_id>' => 'options',
                        'OPTIONS list-of-countries/<restaurant_uuid>' => 'options',
                        'OPTIONS list-of-areas/<restaurant_uuid>/<country_id>' => 'options',

                        'OPTIONS <delivery_zone_id>/<store_uuid>' => 'options',
                        'OPTIONS cancel-override/<delivery_zone_id>/<store_uuid>' => 'options',
                        'OPTIONS <delivery_zone_id>' => 'options',
                        'OPTIONS cancel-override/<delivery_zone_id>' => 'options',
                    ]
                ],

                [// ApplePayController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment/apple-pay',
                    'pluralize' => false,
                    'patterns' => [
                        'POST process-payment' => 'process-payment',
                        'POST validate-merchant' => 'validate-merchant',
                        // OPTIONS VERBS
                        'OPTIONS process-payment' => 'options',
                        'OPTIONS validate-merchant' => 'options',
                    ]
                ],
                [// MoyasarController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment/moyasar',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'index',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [// StripeController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment/stripe',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'index',
                        'GET callback' => 'callback',
                        'POST callback' => 'callback',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [// OpeningHoursController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/opening-hours',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET <day_of_week>/<store_uuid>' => 'detail',
                        'GET <day_of_week>' => 'detail',
                        'POST <store_uuid>' => 'create',
                        'POST' => 'create',
                        'PATCH <day_of_week>' => 'update',
                        'DELETE <opening_hour_id>/<store_uuid>' => 'delete',
                        'DELETE <opening_hour_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <day_of_week>' => 'options',
                        'OPTIONS <opening_hour_id>/<store_uuid>' => 'options',
                        'OPTIONS <opening_hour_id>' => 'options',
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
                        'PATCH <web_link_id>' => 'update',
                        'DELETE <web_link_id>/<store_uuid>' => 'delete',
                        'DELETE <web_link_id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS <web_link_id>/<store_uuid>' => 'options',
                        'OPTIONS <web_link_id>' => 'options',
                    ]
                ],
                [// RestaurantBillingAddressController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/restaurant-billing-address',
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'POST create' => 'create',
                        'PATCH <rba_uuid>/<store_uuid>' => 'update',
                        'PATCH <rba_uuid>' => 'update',
                        'DELETE <rba_uuid>/<store_uuid>' => 'delete',
                        'DELETE <rba_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS detail' => 'options',
                        'OPTIONS <rba_uuid>/<store_uuid>' => 'options',
                        'OPTIONS <rba_uuid>' => 'options',
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
                        'PATCH update-status/<id>/<store_uuid>' => 'change-status',
                        'PATCH update-status/<id>' => 'change-status',
                        'PATCH <id>' => 'update',
                        'DELETE delete-variant-image/<id>/<image>' => 'delete-variant-image',
                        'DELETE delete-image/<id>/<image>' => 'delete-image',
                        'DELETE delete-video/<id>' => 'delete-video',
                        'DELETE <id>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS export-to-excel' => 'options',
                        'OPTIONS items-report' => 'options',
                        'OPTIONS <id>' => 'options',
                        'OPTIONS update-position' => 'options',
                        'OPTIONS update-stock' => 'options',
                        'OPTIONS delete-variant-image/<id>/<image>' => 'options',
                        'OPTIONS update-status/<id>/<store_uuid>' => 'options',
                        'OPTIONS update-status/<id>' => 'options',
                        'OPTIONS delete-image/<id>/<image>' => 'options',
                        'OPTIONS delete-video/<id>' => 'options',
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
                [// PaymentMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment-method',
                    'pluralize' => false,
                    'patterns' => [
                        'POST config/<code>' => 'config',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS config/<code>' => 'options',
                    ]
                ],
                [// ShippingMethodController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/shipping-method',
                    'pluralize' => false,
                    'patterns' => [
                        'POST config/<code>' => 'config',
                        'DELETE disable/<code>' => 'config',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS disable/<code>' => 'options',
                        'OPTIONS config/<code>' => 'options',
                    ]
                ],
                [// InvoiceController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/invoice',
                    'patterns' => [
                        'GET' => 'list',
                        'GET callback' => 'callback',
                        'GET <id>' => 'detail',
                        'POST payment-webhook' => 'payment-webhook',
                        'PATCH payment-webhook' => 'payment-webhook',
                        'POST pay-by-tap' => 'pay-by-tap',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <id>' => 'options',
                    ]
                ],
                [// PageController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/page',
                    'patterns' => [
                        'GET' => 'list',
                        'GET <page_uuid>' => 'detail',
                        'POST' => 'create',
                        'PATCH <page_uuid>' => 'update',
                        'DELETE <page_uuid>' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS <page_uuid>' => 'options',
                    ]
                ],
                [// StoreController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/store',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'detail',
                        'GET status' => 'status',
                        'GET settings/<code>' => 'settings',
                        'GET test-tap' => 'test-tap',
                        'GET view-payment-methods' => 'view-payment-methods',
                        'GET view-payment-methods/<id>' => 'view-payment-methods',
                        'GET view-shipping-methods' => 'view-shipping-methods',
                        'GET log-email-campaign/<id>' => 'log-email-campaign',

                        'POST upload-apple-domain-association' => "upload-apple-domain-association",
                        'POST' => 'update',
                        'POST upgrade' => 'upgrade',
                        'POST create' => 'create',
                        'POST connect-domain' => 'connect-domain',
                        'POST disable-payment-method/<id>/<paymentMethodId>' => 'disable-payment-method',
                        'POST enable-payment-method/<id>/<paymentMethodId>' => 'enable-payment-method',
                        'POST create-tap-account/<id>' => 'create-tap-account',
                        'POST create-tap-queue/<id>' => 'create-tap-queue',
                        'POST upload-docs/<id>' => 'upload-docs',
                        'POST update-business-details/<id>' => 'update-business-details',

                        'POST enable-online-payment/<id>' => 'enable-online-payment',
                        'POST disable-online-payment/<id>' => 'disable-online-payment',
                        'POST enable-cod/<id>' => 'enable-cod',
                        'POST disable-cod/<id>' => 'disable-cod',

                        'POST enable-tabby' => 'enable-tabby',
                        'POST disable-tabby' => 'disable-tabby',
                        'POST enable-tabby/<id>' => 'enable-tabby',
                        'POST disable-tabby/<id>' => 'disable-tabby',

                        'POST enable-moyasar' => 'enable-moyasar',
                        'POST disable-moyasar' => 'disable-moyasar',
                        'POST enable-moyasar/<id>' => 'enable-moyasar',
                        'POST disable-moyasar/<id>' => 'disable-moyasar',

                        'POST enable-upayment' => 'enable-upayment',
                        'POST disable-upayment' => 'disable-upayment',

                        'POST enable-stripe' => 'enable-stripe',
                        'POST disable-stripe' => 'disable-stripe',
                        'POST enable-stripe/<id>' => 'enable-stripe',
                        'POST disable-stripe/<id>' => 'disable-stripe',

                        'POST enable-free-checkout/<id>' => 'enable-free-checkout',
                        'POST disable-free-checkout/<id>' => 'disable-free-checkout',
                        'POST update-analytics-integration/<id>' => 'update-analytics-integration',
                        'POST update-delivery-integration/<id>' => 'update-delivery-integration',
                        'POST enable-cod' => 'enable-cod',
                        'POST disable-cod' => 'disable-cod',
                        'POST enable-free-checkout' => 'enable-free-checkout',
                        'POST disable-free-checkout' => 'disable-free-checkout',
                        
                        'POST update-analytics-integration' => 'update-analytics-integration',
                        'POST update-delivery-integration' => 'update-delivery-integration',
                        'POST update-layout' => 'update-layout',
                        'POST update-bank-account' => 'update-bank-account',
                        'POST update-email-settings' => 'update-email-settings',
                        'POST update-store-settings' => 'update-store-settings',

                        "PATCH update-kyc/<id>" => "update-kyc",
                        'PATCH update-status/<id>/<status>' => 'update-store-status',
                        'PATCH process-gateway-queue/<id>' => 'process-gateway-queue',
                        'PATCH deactivate' => 'deactivate',
                        'POST deactivate' => 'deactivate',
                        'PATCH process-gateway-queue' => 'process-gateway-queue',
                        'POST remove-store' => 'delete',
                        'PATCH remove-store' => 'delete',
                        'PATCH delete-store' => 'delete',
                        'DELETE delete-store' => 'delete',
                        'DELETE remove-gateway-queue/<id>' => 'remove-gateway-queue',
                        'DELETE remove-gateway-queue' => 'remove-gateway-queue',
                        'DELETE' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS upload-apple-domain-association' => "options",
                        'OPTIONS' => 'options',
                        'OPTIONS upload-docs/<id>' => 'options',
                        'OPTIONS update-business-details/<id>' => 'options',
                        'OPTIONS log-email-campaign/<id>' => 'options',
                        'OPTIONS deactivate' => 'options',
                        'OPTIONS remove-store' => 'options',
                        'OPTIONS delete-store' => 'options',
                        'OPTIONS test-tap' => 'options',
                        'OPTIONS upgrade' => 'options',
                        'OPTIONS enable-moyasar' => 'options',
                        'OPTIONS disable-moyasar' => 'options',
                        'OPTIONS enable-moyasar/<id>' => 'options',
                        'OPTIONS disable-moyasar/<id>' => 'options',
                        'OPTIONS enable-upayment/<id>' => 'options',
                        'OPTIONS disable-upayment/<id>' => 'options',
                        'OPTIONS create' => 'options',
                        'OPTIONS enable-stripe' => 'options',
                        'OPTIONS disable-stripe' => 'options',
                        'OPTIONS enable-stripe/<id>' => 'options',
                        'OPTIONS disable-stripe/<id>' => 'options',

                        'OPTIONS connect-domain' => 'options',
                        'OPTIONS update-bank-account' => 'options',
                        'OPTIONS update-delivery-integration/<id>' => 'options',
                        'OPTIONS update-analytics-integration/<id>' => 'options',
                        'OPTIONS disable-payment-method/<id>/<paymentMethodId>' => 'options',
                        'OPTIONS enable-payment-method/<id>/<paymentMethodId>' => 'options',
                        'OPTIONS view-payment-methods/<id>' => 'options',
                        'OPTIONS view-shipping-methods' => 'options',
                        'OPTIONS create-tap-account/<id>' => 'options',
                        'OPTIONS create-tap-queue/<id>' => 'options',
                        'OPTIONS enable-online-payment/<id>' => 'options',
                        'OPTIONS disable-online-payment/<id>' => 'options',
                        'OPTIONS enable-cod/<id>' => 'options',
                        'OPTIONS disable-cod/<id>' => 'options',
                        'OPTIONS enable-free-checkout/<id>' => 'options',
                        'OPTIONS disable-free-checkout/<id>' => 'options',
                        'OPTIONS update-layout' => 'options',
                        'OPTIONS update-status/<id>/<status>' => 'options',
                        'OPTIONS status' => 'options',
                        'OPTIONS settings/<code>' => 'options',
                        'OPTIONS update-email-settings' => 'options',
                        'OPTIONS update-store-settings' => 'options',
                        'OPTIONS process-gateway-queue/<id>' => 'options',
                        'OPTIONS remove-gateway-queue/<id>' => 'options',
                        'OPTIONS enable-cod' => 'options',
                        'OPTIONS disable-cod' => 'options',
                        'OPTIONS enable-free-checkout' => 'options',
                        'OPTIONS disable-free-checkout' => 'options',
                        'OPTIONS update-analytics-integration' => 'options',
                        'OPTIONS update-delivery-integration' => 'options',
                        'OPTIONS process-gateway-queue' => 'options',
                        'OPTIONS remove-gateway-queue' => 'options',
                        "OPTIONS update-kyc/<id>" => 'options'
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
                       'GET price' => 'price',
                       'GET apple-pay-params/<id>' => "apple-pay-params",
                       'GET <id>' => 'view',
                       'POST confirm' => 'confirm',
                       'POST payment-webhook' => 'payment-webhook',
                       'PATCH payment-webhook' => 'payment-webhook',
                       // OPTIONS VERBS
                       'OPTIONS <id>' => 'options',
                       'OPTIONS apple-pay-params/<id>' => "options",
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
                [ // DomainRequest
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'v1/domain-request',
                   'patterns' => [
                       'GET' => 'index',
                       // OPTIONS VERBS
                       'OPTIONS' => 'options',
                   ]
               ],

            ],
        ],
    ],
    'params' => $params,
];
