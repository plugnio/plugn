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
            'enableSchemaCache' => false,
            // Duration of schema cache.
            'schemaCacheDuration' => 300, // 5 mnts
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'eventManager' => [
            'class' => 'common\components\EventManager',
            "sqsRagion" => "eu-west-2",
            "sqsKey" => "AKIAWMITDJRKXNWDOBNJ",
            "sqsSecret" => "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",
            "sqsQueue" => "438663597141/Plugn"
        ],
        'walletManager' => [
            'class' => 'common\components\WalletManager',
            'apiKey' => 'POAO-BiBxj-Oqp2XOIDZgSDrTYJxOa3M',
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
               /* [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => [ 'warning','error'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*', 'console\*','crm\*','api\*','agent\*'],
                ],*/
                [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['info', 'warning','error'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*', 'console\*','crm\*','api\*','agent\*'],
                ],
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            //'class' => 'yii\caching\FileCache',
        ],
        //aws
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'email-smtp.eu-west-1.amazonaws.com',
                'username' => 'AKIAWMITDJRKUESNXW5I',
                'password' => 'BNLEls4MLvkjiAltRpWLTic7IMwKhggzqRVpHU5C9TFh',
                'port' => 587,
            ]
        ],
/*
 *
                'host' => 'smtp.elasticemail.com',
                'username' => 'no-reply@mail.plugn.site',
                'password' => 'E5533D22AF72CD0C79C9ADE5BA11FA7A98AC',
                'port' => 2525,
                'encryption' => 'tls'

        //mailgun
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'transport' => [

                'class' => 'Swift_SmtpTransport',
                'host' => 'email-smtp.eu-west-1.amazonaws.com',
                'username' => 'AKIAWMITDJRKVNB2AFUL',//AKIAWMITDJRKTH5HBB2O //AKIAWMITDJRKTQGXUQT3
                'password' => 'BFXl6illZPE3NP5EQrVNbCO+gMBCopuIi/uy5nwCsUZ6',//BKyPcINpZJsEVnUrMGymff27eaIztgNwSWN7xI2960eJ //GDkiUbOkIxx4qpd0fcksh//0qKvAITbj4PCywBjh
                'port' => '587  ',
                'encryption' => 'tls',
                // 'username' => 'AKIAWMITDJRKTQGXUQT3',//AKIAWMITDJRKTH5HBB2O
                // 'password' => 'GDkiUbOkIxx4qpd0fcksh//0qKvAITbj4PCywBjh',//BKyPcINpZJsEVnUrMGymff27eaIztgNwSWN7xI2960eJ

                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.elasticemail.com',
                'username' => 'support@plugn.io',
                'password' => 'CD006D3ED0C5BD796D1D2C77B111CC24AF9E',
                'port' => '2525',
                'encryption' => 'tls'

               'class' => 'Swift_SmtpTransport',
               'host' => 'email-smtp.eu-west-1.amazonaws.com',
               'username' => 'AKIAWMITDJRKVNB2AFUL',//AKIAWMITDJRKTH5HBB2O //AKIAWMITDJRKTQGXUQT3
               'password' => 'BFXl6illZPE3NP5EQrVNbCO+gMBCopuIi/uy5nwCsUZ6',//BKyPcINpZJsEVnUrMGymff27eaIztgNwSWN7xI2960eJ //GDkiUbOkIxx4qpd0fcksh//0qKvAITbj4PCywBjh
               'port' => '587',
               'encryption' => 'tls',

               */

                /*
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.eu.mailgun.org',
                'username' => 'postmaster@plugn.io',
                'password' => '2d43d23b68911184532de9f81810f5ca-18e06deb-d74119cb',
                'port' => '587',
                'encryption' => 'tls',
                // 'plugins' => [
                //     [
                //         'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                //     ],
                // ],
            ],
        ],
        //sendgrid
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
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
        //microservices todo: for docker 
        'blogManager' => [
            'class' => 'common\components\BlogManager',
            'apiEndpoint' => 'http://localhost:8080/v1',
            'token' => 'Lu4vPW4Npfgce6WkXdt9OErpxXdB7GW4'
        ],
        'gpt' => [
            'class' => 'common\components\GptComponent',
            'token' => 'QSw2ByGUITXFNjJVNNjyzxdbvYP9rXbG',
            'apiEndpoint' => 'http://ec2-18-169-243-163.eu-west-2.compute.amazonaws.com:8083/'
        ],
    ],
];
