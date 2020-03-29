<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'currencyCode' => 'KWD',
            'defaultTimeZone' => 'Asia/Kuwait',
            'timeZone' => 'Asia/Kuwait',
            'timeFormat' => 'h:i:s'
        ],
        'cloudinaryManager' => [
            'class' => 'common\components\CloudinaryManager',
            'cloud_name' => 'plugn',
            'api_key' => '699963168546398',
            'api_secret' => 'SH2PbVsEsRT9Db257Pn9ZDgHGAU'
        ],
       'ownedAccountManager' => [ //Component for agent to manage Owned Restaurant
            'class' => 'common\components\OwnedAccountManager',
        ],
    'tapPayments' => [
            'class' => 'common\components\TapPayments',
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
              'liveApiKey' => "sk_live_9maCt8rNvqXxJfjoRehPITU1", // BeOrganic Honey Key
            // 'liveApiKey' => "sk_live_7nQ5fVq9IbX6JdrgBcoNx3yw", // Cavaraty key
            // 'liveApiKey' => "sk_live_as95jrPDz7W8QLtIuVNoCA6n", // Caro [Vodavoda] Payment Gateway
            // 'liveApiKey' => "sk_live_yC9SwZInzqpjU76QhAVMDXB3", // TheCapital Gateway
            'testApiKey' => "sk_test_cV4aXHefpuWlOG2xkmDIAq9b"
        ],
    ],
];
