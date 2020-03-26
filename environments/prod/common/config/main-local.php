<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'apikey',
                'password' => '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',
                'port' => '587',
                'encryption' => 'tls',
                'plugins' => [
                    [
                        'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                    ],
                ],
            ],
        ],
    ],
];
