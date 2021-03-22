<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'name' => 'Plugn',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
        'thousandSeparator' => ',',
        'decimalSeparator' => '.',
            'defaultTimeZone' => 'Asia/Kuwait',
            'timeZone' => 'Asia/Kuwait',
            'timeFormat' => 'h:i:s'
        ],
        'i18n' => [
            'translations' => [
                'api' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
        'cloudinaryManager' => [
            'class' => 'common\components\CloudinaryManager',
            'cloud_name' => 'plugn',
            'api_key' => '699963168546398',
            'api_secret' => 'SH2PbVsEsRT9Db257Pn9ZDgHGAU'
        ],
        'accountManager' => [//Component for agent to manage Restaurant
            'class' => 'common\components\AccountManager',
        ],
        'tapPayments' => [
            'class' => 'common\components\TapPayments',
            'gatewayToUse' => \common\components\TapPayments::USE_LIVE_GATEWAY,
            'plugnLiveApiKey' => "sk_live_k31q5ActS9shuYgwa8LZ746X",
            'plugnTestApiKey' => "sk_test_p07NquMX4HgwLT8mycdJnZv5",
            'destinationId' => "2663705",
        ],
        'myFatoorahPayment' => [
            'class' => 'common\components\MyFatoorahPayment',
            'gatewayToUse' => \common\components\MyFatoorahPayment::USE_LIVE_GATEWAY,
            'liveApiKey' => "_YrBT-u8QxRfG1yWYUUMRLaeq8uqCpuwXNv9iV19LZd7Swda_-zAc7EkfhB2Sv8HKC5J0jeBxa6jmxXIo-2xv5vIomufUyyuLGYyh76PFY57_FqbNOH7oivXWDtr5R_SCzvkDG8ejsmfh01CuB3xbu06FRnDnFD_BPR0GOr6V_9h8IIylHsQec0ltNpIUe0cdZXnMmFZXPXVcwdGeOPe59PhLwNwuyCkSFxlwpQF-GVgGIQ5raYIMxRs1Rnrz2EgYtem7E4wN1h-Fm1n4evxw0up-QVlPDa2PPtXJSeprEVXg9r0BfD6e8ReCHM1jgzr9cHrwGbNlwMP5P6oQFQyEeVx9jZO0vwt1bGQtfgtXNZiG-NfEkqmJZaYo_rQwG-PxPBv418DWtIxkb_KuY1v-NMxLELJhb1ZGNK1r-SJGp0MDq-rnsBUSPf1b-P3r5H42CaodOs9NhVbk00JP0BloBKxTZa4KNfG-2zve8XOR4IRfC-PKXwZg--7mDT6ouRIF6ylbkJ4ZzJiXa9SmHdtbAQ5WIJMzQY0Aok89_vNtqAaM7MOTZoEubQTgC0HZKGEebPIOy0hRrtPVtfHXUcF1Qt8X6V9p8ZRB-KpzWow0WJf3520EFQqck4WvmId-4t7djl2exH_uhF55qVwgVkX2AmDp1lWvEJCGiudLT1YJ0FmgIxa",
            'testApiKey' => "_YrBT-u8QxRfG1yWYUUMRLaeq8uqCpuwXNv9iV19LZd7Swda_-zAc7EkfhB2Sv8HKC5J0jeBxa6jmxXIo-2xv5vIomufUyyuLGYyh76PFY57_FqbNOH7oivXWDtr5R_SCzvkDG8ejsmfh01CuB3xbu06FRnDnFD_BPR0GOr6V_9h8IIylHsQec0ltNpIUe0cdZXnMmFZXPXVcwdGeOPe59PhLwNwuyCkSFxlwpQF-GVgGIQ5raYIMxRs1Rnrz2EgYtem7E4wN1h-Fm1n4evxw0up-QVlPDa2PPtXJSeprEVXg9r0BfD6e8ReCHM1jgzr9cHrwGbNlwMP5P6oQFQyEeVx9jZO0vwt1bGQtfgtXNZiG-NfEkqmJZaYo_rQwG-PxPBv418DWtIxkb_KuY1v-NMxLELJhb1ZGNK1r-SJGp0MDq-rnsBUSPf1b-P3r5H42CaodOs9NhVbk00JP0BloBKxTZa4KNfG-2zve8XOR4IRfC-PKXwZg--7mDT6ouRIF6ylbkJ4ZzJiXa9SmHdtbAQ5WIJMzQY0Aok89_vNtqAaM7MOTZoEubQTgC0HZKGEebPIOy0hRrtPVtfHXUcF1Qt8X6V9p8ZRB-KpzWow0WJf3520EFQqck4WvmId-4t7djl2exH_uhF55qVwgVkX2AmDp1lWvEJCGiudLT1YJ0FmgIxa"
        ],
        'armadaDelivery' => [
            'class' => 'common\components\ArmadaDelivery',
            'keyToUse' => \common\components\ArmadaDelivery::USE_LIVE_KEY,
        ],
        'smsComponent' => [
            'class' => 'common\components\SmsComponent'
        ],
        'fileGeneratorComponent' => [
            'class' => 'common\components\FileGeneratorComponent'
        ],
        'mashkorDelivery' => [
            'class' => 'common\components\MashkorDelivery',
            'keyToUse' => \common\components\MashkorDelivery::USE_LIVE_KEY
        ],
        'netlifyComponent' => [
            'class' => 'common\components\NetlifyComponent',
            'token' => 'dIaf1ZOTSo-XWIaf7OHy8AgZGMkg9l90E1RWPenKxCs'
        ],
        'githubComponent' => [
            'class' => 'common\components\GithubComponent',
            'token' => '12f36b57c96399bbee096fa2c8f858d06eef883a',
            'branch' => 'master'
        ],
        'slack' => [
            'class' => 'understeam\slack\Client',
            'url' => 'https://hooks.slack.com/services/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',
            'username' => 'plugn',
        ],
        'httpclient' => [
            'class' => 'yii\httpclient\Client',
        ],
    ],
];
