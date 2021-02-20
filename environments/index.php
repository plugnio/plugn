<?php
return [
    'Development [Saoud]' => [
        'path' => 'dev-saoud',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'backend/web/uploads/project-files',
            'console/runtime',
            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/uploads/',
            'api/runtime',
            'api/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'shortner/runtime'
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
            'fixture-generate-and-load'
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'common/config/codeception-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
            'agent/config/main-local.php',
            'shortner/config/main-local.php',
        ],
    ],
    'Development [Khalid]' => [
        'path' => 'dev-khalid',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'backend/web/uploads/project-files',
            'console/runtime',
            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/uploads/',
            'api/runtime',
            'api/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'shortner/runtime'
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
            'fixture-generate-and-load'
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'common/config/codeception-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
            'agent/config/main-local.php',
        ],
    ],
    'Dev-Server' => [
        'path' => 'dev-server',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'backend/web/uploads/project-files',
            'console/runtime',
            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/uploads/',
            'api/runtime',
            'api/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'shortner/runtime'
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
            'fixture-generate-and-load'
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'common/config/codeception-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
            'agent/config/main-local.php',
            'shortner/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'backend/web/uploads/project-files',
            'console/runtime',
            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/uploads/',
            'api/runtime',
            'api/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'shortner/runtime'
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
            'agent/config/main-local.php',
            'shortner/config/main-local.php',
        ],
    ],
];
