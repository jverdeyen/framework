<?php 
namespace Lib;

class Autoloader {
  
  private static $_instance = null;
  private $base_dir;
  
  private function __construct() {}
  

  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  public function register($base_dir) {
    $this->base_dir = $base_dir;

    ini_set('unserialize_callback_func', 'spl_autoload_call');
    spl_autoload_register(array($this, 'autoload'));
    
    require_once(dirname(__FILE__).'/vendor/Twig/Autoloader.php');
    \Twig_Autoloader::register();
    
    require_once(dirname(__FILE__).'/vendor/Twig/Extensions/Autoloader.php');
    \Twig_Extensions_Autoloader::register();
  }
  
  private function autoload($class) {
    
    // Enkel Applicatie classes laden, en Lib data
    if((strpos($class,APP_NAME) === false) && (strpos($class,'Framework') === false)) 
      return false;
      
    $class = explode('\\',str_replace(APP_NAME.'\\','',$class)); // Strip Application name
    $filename = end($class).'.php';
    array_pop($class);
    include $this->base_dir.implode('/',array_map('strtolower', $class)).'/'.$filename;
  }
}