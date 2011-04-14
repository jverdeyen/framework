<?php
namespace Framework\Tests;
use Framework;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require_once dirname(__FILE__) .'/../src/Autoloader.php';

define(ROOT_DIR,dirname(__FILE__) .'/../../');
define(APP_NAME,'Dummy');

define(BACKEND_APP_NAME,'backend');
define(BACKEND_TEMPLATE_DIR,ROOT_DIR.'templates/'.BACKEND_APP_NAME.'/');

define(FRONTEND_APP_NAME,'frontend');
define(FRONTEND_TEMPLATE_DIR,ROOT_DIR.'templates/'.FRONTEND_APP_NAME.'/');

define(APPS,serialize(
          array(
                'admin' => array('name' => BACKEND_APP_NAME, 'url' => ADMIN_URL),
                'www' => array('name' => FRONTEND_APP_NAME, 'url' => ROOT_URL),
                'default' => array('name' => FRONTEND_APP_NAME, 'url' => ROOT_URL)
                )
              ));

define(PHOTO_DIR,ROOT_DIR.'data/photo/');
define(PHOTO_WEB_DIR,ROOT_DIR.'web/img/photo/');
define(PHOTO_CACHE_DIR,ROOT_DIR.'data/photo_cache/');
define(TEMPLATE_CACHE_DIR,ROOT_DIR.'data/templates_cache/');

define(DEFAULT_CONTROLLER, 'index');
define(DEFAULT_ACTION, 'index');
define(DEFAULT_LANGUAGE, 'nl');
define(COOKIE_NAME_LANGUAGE,'dummy-language');

define(LANGUAGES,serialize(array( 1 => 'nl', 2 => 'fr', 3 => 'de', 4 => 'en')));
define(MULTI_LANGUAGE, true);

Framework\Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__) . '/../src/');
Framework\Autoloader::getInstance()->register();
?>