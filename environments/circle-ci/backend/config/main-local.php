<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IPzstcYT6LrNZ7AsUzf8Zz5XtEtX1',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/backend/web/',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
        ],
    ],
];

return $config;
