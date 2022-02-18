<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=plugn',
            'username' => 'root',
            'password' => 'saoud',
            'charset' => 'utf8mb4',
            // Enable Caching of Schema to Reduce SQL Queries
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 60, // 1 minute
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
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
                        'environment' => 'develop',
                    ],
                    'context' => true // Write the context information. The default is true.
                ],
                [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['info', 'warning','error'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*', 'console\*','api\*','agent\*'],
                ],
            ],
        ],
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
        ],
        'cache' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'plugn-redis.0x1cgp.0001.euw2.cache.amazonaws.com',
                'port' => 6379,
                'database' => 2,
            ]
        ],
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
        ],
        'myFatoorahPayment' => [
            'gatewayToUse' => \common\components\MyFatoorahPayment::USE_TEST_GATEWAY
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
            'branch' => 'develop'
        ],
    ],
];
