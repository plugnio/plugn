<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Plugn',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'assetManager' => [
          'linkAssets' => true,
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'auth0' => [
            'class' => 'common\components\Auth0',
            'domain' => 'bawes.us.auth0.com',
            'clientId' => "zBLi5rqikntjIFqS4iJY7RQx6445yf5w",
            'clientSecret' => "Dt9rgs6ghpEHqKHLJf5NDp8Sps26U7OE65eYYBc3AHiWQjUNCkrjelvU18-1tCis",
            'cookieSecret' => "woZaulpAn0qo24K1Ve6dzBgw__936d9m"// Yii::$app->request->cookieValidationKey,
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
