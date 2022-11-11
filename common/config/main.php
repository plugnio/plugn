<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'name' => 'Plugn',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        /*'cache' => [
            'class' => 'yii\caching\FileCache',
        ],*/
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
        'config' => [
            'class' => 'common\components\Config'
        ],
        'auth0' => [
            'class' => 'common\components\Auth0',
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
            'kuwaitLiveApiKey' => "OVWCM41BBSyDFdeismmJ5SjOiu6li8cz_TK60J3X0SzfFlCqCb0HbUhSpOPYMO1KYje9cDvbMNAQyUC6aOjyUuksehAxah4Kb7ArWdabz4yJaBRS4TJ5n8EUFkGiBz71LDENQPx57C9D1kB0KoX1cx5S9EYZp3GJnz-9Y6mFZTUK8HyhxVDL5JR88KTVOV3BdDmjk85UaM9_1bud_D7wUePd7etbY-3hG-eSlaLsSnJ4mmAW_F7RRIy1537xfJsof8fY6vn9xqUEatFbKHEwhBwRMlkaMNKt3eWfwTZ0An6ncW2afSWXIB1F5PWw8HI2si6BNhPzApdbWq62Pd3KnAPX1ie-8gxx0cC1StDb4TH1Fl66UO2Dpd7exp4BYuHLw3I-wrhsj6HbKomJTIOdadXpIA9Bwey1fNHnKBsd4CR56qs1BoYpcdacxzrcZDGfS0a0feYL8OndBpz6bLGvE04Ls4I0hkGxMKQVTyfQ1r7ysLlxTFd40l4k9qcPmC0Qfa2q1HzfaP30aajmfeOyB1MjwPx8mtv6GU2Zf7fKU9nfVPuGRk6MvW7mKQC586ZFEe627TCKC5q47FbzdQ3Qc0YEAtQx7KdstOqy72h3V5tKUZClza8qTdh5RClTHjV3GJ6CV0SrSzV7zTTqehpkuhRk5je0G7DTOZAewRPRhAbNPADIM32ifSWEmJ1-SExyqD5jZw",
            'kuwaitTestApiKey' => "rrKPZpms2ppwtCOSru2ReWqhurwAYWzkN35hTKcDMV0FS0FBLAQU49ziNA1iF-xm_wD7KFP4U3TBykw1xcS1zEYpGCXc3yxVqQYhvILC38F1mse4Xd02hr17XowLH-Ut_QeCQeJVPzds6euwo_HMytGYphcOVQIw5ATer97Vo52CtVxACeTcmLcO_QO8XMJZUKVbAtFlpu0BLvRI6GkdKIq7cw1lWvEWxOzjHebFLJvl7GpV0qpIdcMfX-tmHKQU-5Xqk95rHMmYm8WiDqouCQu8MUtpyayZfWAwJJ5AkqkbbGu1jvbPiuMu3Q6DQph_dMcrdhRwkJmITifopEQhk6jH_WzKx3_1alNRAb6O4wuSEYTVp9S6xaDDumiee906tuT-KIM-HKTVft83_kd33b1ce9RoPtLzV6SyJv44VTsIURXVU0kPYCf7GVPxprOvBTcbW4y8HQRDQnWl8-6WduL2Y_ExW6tGH4L398PfqalY54BUjYaMdpuKMkAxAgWXkdsrnTZxGIdz4Ee8_ap418R5Hyv3Ghmx4OYPWfsf2uBQ4GkXQwBOJv3Ku9rgPue0vU_ADBeth9Y8fifZTRjMRU_M2PmQHmrurxfle_XDy6taBZ7mWhvS1EtRuAa_eEz9QKZBDGcXWIVORbHMIx7cA9CKXiHKJkthNxO2awGK65rKYgU6",
            'saudiLiveApiKey' => "e-hU0p8hjiSmM8YB5kijOoouH3wsXvnoT6tVfKrSaxWuIaMVyyO-V4ikyeN1Gs2E4KlbZc4xsXlsZL8mDnoIbmw6dK6dOK0lH7dfRm6oLJD-sUdy3P5HzgnUl8yDUkxGLXL1dD9Dn1gu_yd86XHuWNUB6BbB4CNzyhZ0eYlAG8l_8n26whIInpjcuQQbN1t-Jia8W3_VlKbaHZh_9CNsR7LVzPbBDlPuBbcnK0KSMMSDWv7jcfna37xq9d-NOXfwMCqmE8fcIdFGpvSIK63spI4vGrwqX0AespVMLdADyENcWi-NN93vsBP93QLvJkfiuLrl4tqlDfkk2NcAXlfIjcjK_TWxUgM0kY0qax3IJhOUWpoa5toxv-iDZM_5yfFioW2D-Denn0_6-HTkmt-MZcSqGK4iWmkYWtSu--WHy6O8ZbcmdZg0Ayp5FyYTKgNg9PfRMKUzf_5hvR3KK_Df6jboho6joLs4PvVi5_pqUmXQcIRZqHe2tvsp3vvZCNQLvIsKtRg9eGJiZXRTzUZ_6CbJUnBGT64i3gha2eAwHlOtNrgNbyr71UIyoPpyykX16fGx2udZDwSi21MCfE1W4-WmU0GqpvDu4yN28oQgsbUEDwRUsGp6qqwwqXwm2_YnaRkRLaRT3PGizBTJSirXm4hISBnQ9Io-k33bVY3gI09KUZhnmjXaUhhb171-oCPhRJhAHQ"
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
            'token' => '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',
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
