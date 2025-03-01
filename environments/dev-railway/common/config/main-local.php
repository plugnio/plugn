<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql.railway.internal:3306;dbname=railway',
            'username' => 'root',
            'password' => 'FbSkwSvXwjsQPEfQrNvNzdLmJuTLFPyo',
            'charset' => 'utf8mb4',
            // Enable Caching of Schema to Reduce SQL Queries
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 60, // 1 minute
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'eventManager' => [
            'class' => 'common\components\EventManager',
            "sqsRagion" => "eu-west-2",
            "sqsKey" => "AKIAWMITDJRKXNWDOBNJ",
            "sqsSecret" => "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",
            "sqsQueue" => "438663597141/PlugnDev"
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis.railway.internal',
            'username' => 'default',
            'password' => 'FaHoFazhmBfIINFxgfVylfWHGuwymvdw',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
           // 'class' => 'yii\caching\FileCache',
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
            'apiEndpoint' => 'http://localhost:8083/'
        ],
        //external app
        'walletManager' => [
            'class' => 'common\components\WalletManager',
            'apiKey' => 'QSw2ByGUITXFNjJVNNjyzxdbvYP9rXbG',
            'apiEndpoint' => 'http://localhost/wallet/webhook/web/v1',
            'companyWalletUserID' => 'user_fcac8a5f-52a2-11ed-a68e-d85ed3a264df'
        ],
        'resourceManager' => [
            'class' => 'common\components\S3ResourceManager',
            'region' => 'eu-west-2', // Bucket based in London
            'key' => 'AKIAWMITDJRKVN5ODY2X',
            'secret' => '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',
            'bucket' => 'plugn-uploads-dev-server',
            /**
             * For Local Development, we access using key and secret
             * For Dev and Production servers, access is via server embedded IAM roles so no key/secret required
             *
             * You can access the bucket with:
             * https://plugn-uploads-dev-server.s3.amazonaws.com/
             * https://plugn-uploads-dev-server.s3.amazonaws.com/folderName/fileName.jpg
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
               /* [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['warning','error'],
                    'categories' => ['backend\*', 'frontend\*', 'common\*', 'console\*','api\*','agent\*'],
                ],*/
            ],
        ],
        // 'mailer' => [
        //          'class' => \yii\symfonymailer\Mailer::class,
        //          'viewPath' => '@common/mail',
        //          // send all mails to a file by default. You have to set
        //          // 'useFileTransport' to false and configure a transport
        //          // for the mailer to send real emails.
        //          'useFileTransport' => true,
        // ],
        
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.elasticemail.com',
                'username' => 'no-reply@mail.plugn.site',
                'password' => 'E5533D22AF72CD0C79C9ADE5BA11FA7A98AC',
                'port' => 2525,
                'encryption' => 'tls'
            ],
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
        'apiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://api.dev.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
        'agentApiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://agent.dev.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ]
    ],
];
