<?php

return [
    'adminEmail' => 'contact@plugn.io',
    'supportEmail' => 'contact@plugn.io',
    // 'senderEmail' => 'noreply@example.com',
    // 'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'bsDependencyEnabled' => false,
    'liveApiKey' => 'sk_test_95DtopzOnv38QSag7mT6WIbs', //TODO change it to sk_live_23JyAf874rMkZwsLjVpQ0WOq
    'testApiKey' => 'sk_test_95DtopzOnv38QSag7mT6WIbs',
    'allowedOrigins' => [
        //'https://api.yo3an.io/',
        //Dev Envs
        null,
        '*' //remove this once app is live
    ]
];
