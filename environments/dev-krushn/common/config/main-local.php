<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=plugn',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ],
        'eventManager' => [
            'class' => 'common\components\EventManager',
            "sqsRagion" => "eu-west-2",
            "sqsKey" => "AKIAWMITDJRKXNWDOBNJ",
            "sqsSecret" => "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",
            "sqsQueue" => "438663597141/PlugnDev"
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'gpt' => [
            'class' => 'common\components\GptComponent',
            'token' => 'QSw2ByGUITXFNjJVNNjyzxdbvYP9rXbG',
            'apiEndpoint' => 'http://localhost:8083/'
        ],
        'mailer' => [
                 'class' => 'yii\swiftmailer\Mailer',
                 'viewPath' => '@common/mail',
                 // send all mails to a file by default. You have to set
                 // 'useFileTransport' to false and configure a transport
                 // for the mailer to send real emails.
                 'useFileTransport' => true,
        ],
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
        
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
        ],
        'armadaDelivery' => [
            'keyToUse' => \common\components\ArmadaDelivery::USE_TEST_KEY,
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
            'baseUrl' => 'http://localhost/plugn/api/web',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ], 
        //microservices
        'blogManager' => [
            'class' => 'common\components\BlogManager',
            'apiEndpoint' => 'http://localhost:8080/v1',
            'token' => 'Lu4vPW4Npfgce6WkXdt9OErpxXdB7GW4'
        ],
        'agentApiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://localhost/plugn/agent/web',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
    ],
];
