<?php define('IN WEB', TRUE);
//require_once('config/config.inc.php');
require('config/config.inc.php');

ini_set('display_errors', "Off");
ini_set('display_startup_errors', "Off");
ini_set('track_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__.'/log.txt');

header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache"); 

error_reporting(PRODUCTION_SERVER ? 0 : (E_ALL ^ E_NOTICE));

date_default_timezone_set('Asia/Shanghai'); 

define('ROOT_PATH', dirname(__FILE__).'/' );
define("NOW", time());
define('API_PATH',  ROOT_PATH . 'api/');
define('CRONTAB_PATH',  ROOT_PATH . 'crontab/');
define('MODEL_PATH',  ROOT_PATH . 'model/');
define('CFG_PATH',  ROOT_PATH . 'config/');

define('SYS_PATH',  ROOT_PATH . 'system/');
define('SYS_LIB_PATH', SYS_PATH  . 'lib/');
define('SYS_HEP_PATH', SYS_PATH  . 'helper/');
define('PUSH_API_PATH', SYS_PATH  . 'api/push/');
define('PAY_API_PATH', SYS_PATH  . 'api/pay/');

define('WEB_PATH',  ROOT_PATH . 'web/');
define('WEB_CFG_PATH',  WEB_PATH . '{module}/config/');
define('WEB_MODEL_PATH',  WEB_PATH . '{module}/model/');

define('ADMIN_PATH',  ROOT_PATH . 'admin/');
define('ADMIN_CFG_PATH',  ADMIN_PATH . '{module}/config/');
define('ADMIN_MODEL_PATH',  ADMIN_PATH . '{module}/model/');

define('LOG_PATH',  ROOT_PATH . 'logs/');

require_once(SYS_LIB_PATH.'/lib.autoload.php');

Autoload::init();

