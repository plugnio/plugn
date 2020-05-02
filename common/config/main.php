<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'name' => 'Plugn',
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
        'i18n' => [
            'translations' => [
                'api' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
        'cloudinaryManager' => [
            'class' => 'common\components\CloudinaryManager',
            'cloud_name' => 'plugn',
            'api_key' => '699963168546398',
            'api_secret' => 'SH2PbVsEsRT9Db257Pn9ZDgHGAU'
        ],
        'ownedAccountManager' => [//Component for agent to manage Owned Restaurant
            'class' => 'common\components\OwnedAccountManager',
        ],
        'tapPayments' => [
            'class' => 'common\components\TapPayments',
            'gatewayToUse' => \common\components\TapPayments::USE_LIVE_GATEWAY,
            'plugnLiveApiKey' => "sk_live_k31q5ActS9shuYgwa8LZ746X",
            'plugnTestApiKey' => "sk_test_p07NquMX4HgwLT8mycdJnZv5",
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*'],
                ],
            ],
        ],
        'slack' => [
            'class' => 'understeam\slack\Client',
            'url' => 'https://hooks.slack.com/services/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',
            'username' => 'plugn',
        ],
        'httpclient' => [
            'class' =>'yii\httpclient\Client',
        ],
    ],
];
