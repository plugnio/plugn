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
        'thousandSeparator' => ',',
        'decimalSeparator' => '.',
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
        'temporaryBucketResourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'region' => 'eu-west-2', // Bucket based in London
            'key' => 'AKIAJXOMRCDE65WKBPUA',
            'secret' => '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',
            'bucket' => 'plugn-public-anyone-can-upload-24hr-expiry'
            /**
             * You can access the Temporary bucket with:
             * https://pogi-public-anyone-can-upload-24hr-expiry.s3.amazonaws.com/
             * https://pogi-public-anyone-can-upload-24hr-expiry.s3.amazonaws.com/folderName/fileName.jpg
             */
        ],
        'accountManager' => [//Component for agent to manage Restaurant
            'class' => 'common\components\AccountManager',
        ],
        'tapPayments' => [
            'class' => 'common\components\TapPayments',
            'gatewayToUse' => \common\components\TapPayments::USE_LIVE_GATEWAY,
            'plugnLiveApiKey' => "sk_live_k31q5ActS9shuYgwa8LZ746X",
            'plugnTestApiKey' => "sk_test_p07NquMX4HgwLT8mycdJnZv5",
            'destinationId' => "2663705",
        ],
        'myFatoorahPayment' => [
            'class' => 'common\components\MyFatoorahPayment',
            'gatewayToUse' => \common\components\MyFatoorahPayment::USE_LIVE_GATEWAY,
            'liveApiKey' => "rrKPZpms2ppwtCOSru2ReWqhurwAYWzkN35hTKcDMV0FS0FBLAQU49ziNA1iF-xm_wD7KFP4U3TBykw1xcS1zEYpGCXc3yxVqQYhvILC38F1mse4Xd02hr17XowLH-Ut_QeCQeJVPzds6euwo_HMytGYphcOVQIw5ATer97Vo52CtVxACeTcmLcO_QO8XMJZUKVbAtFlpu0BLvRI6GkdKIq7cw1lWvEWxOzjHebFLJvl7GpV0qpIdcMfX-tmHKQU-5Xqk95rHMmYm8WiDqouCQu8MUtpyayZfWAwJJ5AkqkbbGu1jvbPiuMu3Q6DQph_dMcrdhRwkJmITifopEQhk6jH_WzKx3_1alNRAb6O4wuSEYTVp9S6xaDDumiee906tuT-KIM-HKTVft83_kd33b1ce9RoPtLzV6SyJv44VTsIURXVU0kPYCf7GVPxprOvBTcbW4y8HQRDQnWl8-6WduL2Y_ExW6tGH4L398PfqalY54BUjYaMdpuKMkAxAgWXkdsrnTZxGIdz4Ee8_ap418R5Hyv3Ghmx4OYPWfsf2uBQ4GkXQwBOJv3Ku9rgPue0vU_ADBeth9Y8fifZTRjMRU_M2PmQHmrurxfle_XDy6taBZ7mWhvS1EtRuAa_eEz9QKZBDGcXWIVORbHMIx7cA9CKXiHKJkthNxO2awGK65rKYgU6",
            'testApiKey' => "rrKPZpms2ppwtCOSru2ReWqhurwAYWzkN35hTKcDMV0FS0FBLAQU49ziNA1iF-xm_wD7KFP4U3TBykw1xcS1zEYpGCXc3yxVqQYhvILC38F1mse4Xd02hr17XowLH-Ut_QeCQeJVPzds6euwo_HMytGYphcOVQIw5ATer97Vo52CtVxACeTcmLcO_QO8XMJZUKVbAtFlpu0BLvRI6GkdKIq7cw1lWvEWxOzjHebFLJvl7GpV0qpIdcMfX-tmHKQU-5Xqk95rHMmYm8WiDqouCQu8MUtpyayZfWAwJJ5AkqkbbGu1jvbPiuMu3Q6DQph_dMcrdhRwkJmITifopEQhk6jH_WzKx3_1alNRAb6O4wuSEYTVp9S6xaDDumiee906tuT-KIM-HKTVft83_kd33b1ce9RoPtLzV6SyJv44VTsIURXVU0kPYCf7GVPxprOvBTcbW4y8HQRDQnWl8-6WduL2Y_ExW6tGH4L398PfqalY54BUjYaMdpuKMkAxAgWXkdsrnTZxGIdz4Ee8_ap418R5Hyv3Ghmx4OYPWfsf2uBQ4GkXQwBOJv3Ku9rgPue0vU_ADBeth9Y8fifZTRjMRU_M2PmQHmrurxfle_XDy6taBZ7mWhvS1EtRuAa_eEz9QKZBDGcXWIVORbHMIx7cA9CKXiHKJkthNxO2awGK65rKYgU6"

        ],
        'armadaDelivery' => [
            'class' => 'common\components\ArmadaDelivery',
            'keyToUse' => \common\components\ArmadaDelivery::USE_LIVE_KEY,
        ],
        'smsComponent' => [
            'class' => 'common\components\SmsComponent'
        ],
        'fileGeneratorComponent' => [
            'class' => 'common\components\FileGeneratorComponent'
        ],
        'mashkorDelivery' => [
            'class' => 'common\components\MashkorDelivery',
            'keyToUse' => \common\components\MashkorDelivery::USE_LIVE_KEY
        ],
        'googleMapComponent' => [
            'class' => 'common\components\GoogleMapComponent',
            'token' => 'AIzaSyCFeQ-wuP5iWVRTwMn5nZZeOE8yjGESFa8'
        ],
        'netlifyComponent' => [
            'class' => 'common\components\NetlifyComponent',
            'token' => 'dIaf1ZOTSo-XWIaf7OHy8AgZGMkg9l90E1RWPenKxCs'
        ],
        'githubComponent' => [
            'class' => 'common\components\GithubComponent',
            'token' => '12f36b57c96399bbee096fa2c8f858d06eef883a',
            'branch' => 'master'
        ],
        'slack' => [
            'class' => 'understeam\slack\Client',
            'url' => 'https://hooks.slack.com/services/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',
            'username' => 'plugn',
        ],
        'httpclient' => [
            'class' => 'yii\httpclient\Client',
        ],
    ],
];
