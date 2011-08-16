<?php 
namespace Framework;

class Autoloader {
  
  private static $_instance = null;
  private $base_dir;
  private $namespaces;
  
  private function __construct() {
    ini_set('unserialize_callback_func', 'spl_autoload_call');
    spl_autoload_register(array($this, 'autoload'));
    
    # pear install twig/twig
    require_once 'Twig/Autoloader.php';
    \Twig_Autoloader::register();
    
    require_once dirname(__FILE__).'/vendor/Twig/Extensions/Autoloader.php';
    \Twig_Extensions_Autoloader::register();
    
    require_once dirname(__FILE__).'/vendor/Assetic/Assetic/Autoloader.php';
    \Assetic_Autoloader::register();
    
    # pear install symfony/YAML
    require_once 'SymfonyComponents/YAML/sfYaml.php';
    
    # sudo pear install pear.doctrine-project.org/DoctrineCommon-2.1.0
    # sudo pear install pear.doctrine-project.org/DoctrineDBAL-2.1.0
    # sudo pear install pear.doctrine-project.org/DoctrineORM-2.1.0
    require_once 'Doctrine/Common/ClassLoader.php';
    $classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
    $classLoader->register();
    
    #sudo pear install swift/swift
    require_once 'swift_required.php';
        
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
  
  private function autoload($class) {
    // Enkel registerd namespaces laden
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
    @include $this->namespaces[strtoupper($first_namespace)].implode('/',array_map('strtolower', $class)).'/'.$filename;
  }
}