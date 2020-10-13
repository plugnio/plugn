<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-shortner',
    'name' => 'Plugn',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'shortner\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-shortner',
        ],
        'assetManager' => [
          'linkAssets' => true,
      ],
        // 'user' => [
        //     'identityClass' => 'common\models\Agent',
        //     'enableAutoLogin' => true,
        //     'identityCookie' => ['name' => '_identity-shortner', 'httpOnly' => true],
        // ],
        'session' => [
            // this is the name of the session cookie used for login on the shortner
            'name' => 'advanced-shortner',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                    'r/<orderId>' => 'shortener/redirect',
                ],
            ],
    ],
    'params' => $params,
];
