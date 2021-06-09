<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class AgentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Agent';
}

// HTTP
define('HTTP_SERVER', 'http://iamkrushn.com/demo/gameking/admin/');
define('HTTP_CATALOG', 'http://iamkrushn.com/demo/gameking/');

// HTTPS
define('HTTP_SERVER', 'https://iamkrushn.com/demo/gameking/admin/');
define('HTTP_CATALOG', 'https://iamkrushn.com/demo/gameking/');

// DIR
define('DIR_APPLICATION', '/home/iamkrrcc/public_html/demo/gameking/admin/');
define('DIR_CATALOG', '/home/iamkrrcc/public_html/demo/gameking/catalog/');
define('DIR_SYSTEM', '/home/iamkrrcc/public_html/demo/gameking/system/');
define('DIR_IMAGE', '/home/iamkrrcc/public_html/demo/gameking/image/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'iamkrrcc_game');
define('DB_PASSWORD', '4E6?rB3_S2*m');//fs7R@%3?*m
define('DB_DATABASE', 'iamkrrcc_gameking');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');


// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');
