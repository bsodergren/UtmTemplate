<?php



use UTM\Utm;
use UTMTemplate\Render;
use UTMTemplate\Template;
use UTMTemplate\UtmDevice;

ob_start();


define('__ROOT_DIRECTORY__', dirname(realpath($_SERVER['CONTEXT_DOCUMENT_ROOT']), 2));
define('__TEST_HTML_DIR__', __ROOT_DIRECTORY__ . '/Test');
define('__COMPOSER_LIB__', __ROOT_DIRECTORY__ . '/vendor');

set_include_path(get_include_path() . \PATH_SEPARATOR . __COMPOSER_LIB__);
require_once __COMPOSER_LIB__ . '/autoload.php';

$utm  = new Utm();
Utm::LoadEnv(__TEST_HTML_DIR__);

// Utm::$SHOW_HTML_DUMP = true;

// register_shutdown_function('utmddump');



define('__HTML_TEMPLATE__', __TEST_HTML_DIR__ . '/Layout/Default');
define('__MOBILE_TEMPLATE__', __TEST_HTML_DIR__ . '/Layout/Mobile');
define('__TPL_CACHE_DIR__', __TEST_HTML_DIR__ . '/var/cache/template/');
define('__LAYOUT_PATH__', __TEST_HTML_DIR__ . '/html/assets');

define('__URL_PATH__', "/test");
define('__URL_HOME__', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . __URL_PATH__);
define('__LAYOUT_URL__', __URL_HOME__ . '/assets');

Template::$USER_TEMPLATE_DIR = __HTML_TEMPLATE__;
Template::$TEMPLATE_COMMENTS = false;
Template::$SITE_URL = __LAYOUT_URL__;
Template::$SITE_PATH = __LAYOUT_PATH__;
Template::$ASSETS_URL = __LAYOUT_URL__ . \DIRECTORY_SEPARATOR . 'Default';
Template::$ASSETS_PATH = __LAYOUT_PATH__ . \DIRECTORY_SEPARATOR . 'Default';
Template::$CACHE_DIR = __TPL_CACHE_DIR__;
Template::$USE_TEMPLATE_CACHE = false;
UtmDevice::$DETECT_BROWSER = false;
UtmDevice::$USER_DEFAULT_TEMPLATE = __HTML_TEMPLATE__;
UtmDevice::$USER_MOBILE_TEMPLATE = __MOBILE_TEMPLATE__;
UtmDevice::$MOBILE_ASSETS_URL = __LAYOUT_URL__ . \DIRECTORY_SEPARATOR . 'Mobile';
UtmDevice::$MOBILE_ASSETS_PATH = __LAYOUT_PATH__ . \DIRECTORY_SEPARATOR . 'Mobile';

$device = new UtmDevice();
