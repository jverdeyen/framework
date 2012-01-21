<?php
namespace Framework\Tests;
use Framework;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require_once dirname(__FILE__) .'/../src/autoloader/Autoloader.php';

define(ROOT_DIR,dirname(__FILE__) .'/../../');
define(APP_NAME,'Dummy');

define(BACKEND_APP_NAME,'backend');
define(BACKEND_TEMPLATE_DIR,ROOT_DIR.'templates/'.BACKEND_APP_NAME.'/');

define(FRONTEND_APP_NAME,'frontend');
define(FRONTEND_TEMPLATE_DIR,ROOT_DIR.'templates/'.FRONTEND_APP_NAME.'/');


define(PHOTO_DIR,ROOT_DIR.'data/photo/');
define(PHOTO_WEB_DIR,ROOT_DIR.'web/img/photo/');
define(PHOTO_CACHE_DIR,ROOT_DIR.'data/photo_cache/');
define(TEMPLATE_CACHE_DIR,ROOT_DIR.'data/templates_cache/');


define(COOKIE_NAME_LANGUAGE,'dummy-language');


//define(MULTI_LANGUAGE, true);

\Framework\Autoloader\Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__) . '/../src/');

?>