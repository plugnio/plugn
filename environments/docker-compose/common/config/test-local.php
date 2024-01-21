<?php
return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=mysql;dbname=plugn_test',
        ],
        'tapPayments' => [
            'gatewayToUse' => \common\components\TapPayments::USE_TEST_GATEWAY,
            "destinationId" => null
        ],
    ],
];
