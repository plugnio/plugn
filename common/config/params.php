<?php

return [
    'appName' => 'Plugn',
    'adminEmail' => 'contact@plugn.io',
    'supportEmail' => 'support@plugn.io',
    'noReplyEmail' => 'no-reply@mail.plugn.site',//contact@plugn.io
    'remailerDomain' => '@remail.plugn.site',
    // 'senderEmail' => 'noreply@example.com',
    // 'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'bsDependencyEnabled' => false,
    'google_api_key' => 'AIzaSyCGCusw5MJ_aJwyzIi4q7pJY71k2CNXAbA',
    'liveApiKey' => 'sk_live_23JyAf874rMkZwsLjVpQ0WOq', //Plugn secrect api key
    'testApiKey' => 'sk_test_95DtopzOnv38QSag7mT6WIbs',
    'myfatoorah.kuwaitSecretKey' => 'WmlCnGR8+MXAlNZ3lyMdW/mD06jXa2kWa44g21lPawoRTMoZpKmn39ihdcQKYKw3uax7QYfhuEK+qPDkIvzfmA==',
    'myfatoorah.saudiSecretKey' => 'sFfT2vIPVu7+GWlGFWqyH47wuVfNrhnqNpg2FCScRDrhoDiEmyvCPKBJcWcPf4takQR21o/PBK/oXfabiq0dUg==',
    'storeVersion' => 5,
    'mailThreshold' => 500,
    "elasticMailIpPool" => "Default",
    'allowedOrigins' => [
        //'https://api.yo3an.io/',
        //Dev Envs
        null,
        'localhost',
        '*' //remove this once app is live
    ]
];
