<?php
namespace Framework\Autoloader;

class Autoloader {

  private static $_instance = null;
  private $base_dir;
  private $namespaces;

  private function __construct() {
    ini_set('unserialize_callback_func', 'spl_autoload_call');
    spl_autoload_register(array($this, 'autoload'));

    require ROOT_DIR.'vendor/autoload.php';
    require_once ROOT_DIR.'/vendor/twig/twig/lib/Twig/Autoloader.php';
    require_once ROOT_DIR.'/vendor/jverdeyen/framework/src/vendor/Twig/Extensions/Autoloader.php';
    require_once ROOT_DIR.'/vendor/webvariants/sfyaml/lib/sfYaml.php';
    require_once ROOT_DIR.'/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
    require_once ROOT_DIR.'/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

    \Twig_Autoloader::register();
    \Twig_Extensions_Autoloader::register();

    $classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
    $classLoader->register();

    $classLoader = new \Doctrine\Common\ClassLoader('Symfony', 'Doctrine');
    $classLoader->register();
  }

  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function registerNamespace($namespace,$dir){
    $this->namespaces[strtoupper($namespace)] = $dir;
  }

  public function autoload($class) {
    $temp = $class;
    $class = explode('\\',$class);
    $first_namespace = ($class[0] == '') ? strtoupper($class[1]) : strtoupper($class[0]);

    if(!is_array($this->namespaces))
      return false;

    if(!array_key_exists($first_namespace, $this->namespaces))
      return false;

    if($class[0] == '')
      unset($class[1]);
    unset($class[0]);

    $filename = end($class).'.php';
    array_pop($class);
    $file =  $this->namespaces[strtoupper($first_namespace)].implode('/',array_map('strtolower', $class)).'/'.$filename;
    if(ENVIRONMENT == 'dev'){
      include $file;
    }else{
      @include $file;
    }
  }
}