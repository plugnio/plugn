<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=plugn-main-latest-cluster.cluster-c8mekjvvbygf.eu-west-2.rds.amazonaws.com;dbname=yo3an',
            'username' => 'yo3an',
            'password' => 'iamyo3an',
            'charset' => 'utf8mb4',
            // common configuration for slaves
            'slaveConfig' => [
                'username' => 'yo3an',
                'password' => 'iamyo3an',
                'attributes' => [
                    // use a smaller connection timeout
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],
            // list of slave configurations for Read-write splitting
            'slaves' => [
                ['dsn' => 'mysql:host=plugn-main-latest-cluster.cluster-ro-c8mekjvvbygf.eu-west-2.rds.amazonaws.com;dbname=yo3an']
            ],
            // Enable Caching of Schema to Reduce SQL Queries
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 300, // 5 mnts
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'walletManager' => [
            'class' => 'common\components\WalletManager',
            'apiKey' => 'imx4kpyVCXbi7sVy-zEvEITL63sQWisn',//QSw2ByGUITXFNjJVNNjyzxdbvYP9rXbG
        ],
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'authMethod' => \common\components\S3ResourceManager::AUTH_VIA_IAM_ROLE,
            'region' => 'eu-west-2', // Bucket based in London
            'bucket' => 'plugn-uploads',
            /**
             * For Local Development, we access using key and secret
             * For Dev and Production servers, access is via server embedded IAM roles so no key/secret required
             *
             * You can access the bucket with:
             * https://plugn-uploads.s3.amazonaws.com/
             * https://plugn-uploads.s3.amazonaws.com/folderName/fileName.jpg
             */
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'notamedia\sentry\SentryTarget',
                    'dsn' => 'https://f6033f8f46ba451abbf4fa2730e8305a:7266a5e7beca44ff96fb32294ca35557@o70039.ingest.sentry.io/5220572',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\BadRequestHttpException',
                        'yii\web\UnauthorizedHttpException',
                        'yii\web\NotFoundHttpException',
                        'yii\web\HttpException:400',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:404',
                    ],
                    'clientOptions' => [
                        //which environment are we running this on?
                        'environment' => 'production',
                    ],
                    'context' => true // Write the context information. The default is true.
                ],
                [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['info', 'warning','error'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*', 'console\*','crm\*','api\*','agent\*'],
                ],
            ],
        ],
        'cache' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'plugn-redis.0x1cgp.0001.euw2.cache.amazonaws.com',
                'port' => 6379,
                'database' => 1,
            ]
        ], 

        //aws

        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => 'email-smtp.eu-west-1.amazonaws.com',
                    'username' => 'AKIAWMITDJRKTH5HBB2O',
                    'password' => 'BKyPcINpZJsEVnUrMGymff27eaIztgNwSWN7xI2960eJ',
                    'port' => '587',
                    'encryption' => 'tls',
            ],
        ],*/

        //mailgun
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.elasticemail.com',
                'username' => 'support@plugn.io',
                'password' => 'CD006D3ED0C5BD796D1D2C77B111CC24AF9E',
                'port' => '2525',
                'encryption' => 'tls'

                /*
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.eu.mailgun.org',
                'username' => 'postmaster@plugn.io',
                'password' => '2d43d23b68911184532de9f81810f5ca-18e06deb-d74119cb',
                'port' => '587',
                'encryption' => 'tls',*/
                // 'plugins' => [
                //     [
                //         'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                //     ],
                // ],
            ],
        ],
        /*
        //sendgrid
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'apikey',
                'password' => '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',
                'port' => '587',
                'encryption' => 'tls',
                // 'plugins' => [
                //     [
                //         'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                //     ],
                // ],
            ],
        ],*/
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_LIVE_GATEWAY,
        ],
        'myFatoorahPayment' => [
            'gatewayToUse' => \common\components\MyFatoorahPayment::USE_LIVE_GATEWAY
        ],
       'armadaDelivery' => [
            'keyToUse' => \common\components\ArmadaDelivery::USE_LIVE_KEY,
        ],
        'mashkorDelivery' => [
            'class' => 'common\components\MashkorDelivery',
            'keyToUse' => \common\components\MashkorDelivery::USE_LIVE_KEY,
        ],
        'githubComponent' => [
            'class' => 'common\components\GithubComponent',
            'branch' => 'master'
        ],
        'apiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://api.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
        'agentApiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://agent.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
    ],
];
