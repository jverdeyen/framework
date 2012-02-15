<?php 
namespace Framework\Autoload;

class App {
  
  private $namespaces;
  
  public function __construct() {
    ini_set('unserialize_callback_func', 'spl_autoload_call');
    spl_autoload_register(array($this, 'autoload'));
  }

  
  public function register($namespace, $dir){
    $this->namespaces[strtoupper($namespace)] = $dir;
  }
  
  public function autoload($class) {
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