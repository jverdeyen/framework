<?php 
namespace Framework;

class Autoloader {
  
  private static $_instance = null;
  private $base_dir;
  private $namespaces;
  
  private function __construct() {}
  

  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  public function registerNamespace($namespace,$dir){
    $this->namespaces[strtoupper($namespace)] = $dir;
  }
  
  public function register() {
    //var_dump($this->namespaces);
    ini_set('unserialize_callback_func', 'spl_autoload_call');
    spl_autoload_register(array($this, 'autoload'));
    
    require_once(dirname(__FILE__).'/vendor/Twig/Autoloader.php');
    \Twig_Autoloader::register();
    
    require_once(dirname(__FILE__).'/vendor/Twig/Extensions/Autoloader.php');
    \Twig_Extensions_Autoloader::register();
  }
  
  private function autoload($class) {
    // Enkel registerd namespaces laden
    $temp = $class;
    $class = explode('\\',$class);    
    $first_namespace = ($class[0] == '') ? strtoupper($class[1]) : strtoupper($class[0]);
    
    if(!array_key_exists($first_namespace, $this->namespaces))
      return false;
    
    if($class[0] == '')
      unset($class[1]);
    unset($class[0]);
      
    $filename = end($class).'.php';  
    array_pop($class);
    include $this->namespaces[strtoupper($first_namespace)].implode('/',array_map('strtolower', $class)).'/'.$filename;
  }
}