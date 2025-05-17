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
                'class' => 'yii\redis\Connection',
                'hostname' => 'redis-xkt_.railway.internal',
                'username' => 'default',
                'password' => 'BGtjhtRKQJvAirawTCZjYrjwRrQAGFBS',
                'port' => 6379,
                'database' => 0,
            ]
        ],
    ],
];
