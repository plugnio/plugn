<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IPzstcYT6LrNZ7AsUzf8Zz5XtEtX1',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'https://admin.plugn.io',
            'enablePrettyUrl' => false,
            'showScriptName' => true,
        ],
        'session' => [
            // Use Redis as a cache
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'plugn-redis.0x1cgp.0001.euw2.cache.amazonaws.com',
                'port' => 6379,
                'database' => 5,
            ]
        ],
    ],
];
