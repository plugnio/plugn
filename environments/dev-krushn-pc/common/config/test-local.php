<?php
return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=plugn_test',
            'username' => 'root',
            'password' => '',
        ],
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
            "destinationId" => null
        ],
    ],
];
