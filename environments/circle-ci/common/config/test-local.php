<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=plugn_test',
            'username' => 'bawes',
            'password' => 'passw0rd',
            'charset' => 'utf8',
        ],
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
            "destinationId" => null
        ],
    ],
];
