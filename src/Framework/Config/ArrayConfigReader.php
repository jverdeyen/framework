<?php
namespace Framework\Config;

Class ArrayConfigReader implements ConfigReaderInterface{
  
  public static $config;
    
  public function __construct($config){
    $this->config = $config;
  }
  
  public function getConfigArray(){
    return $this->config;
  }
  
  public function add($source){
    if(is_array($source)){
      $this->config = array_merge($this->config,$source);
    }
  }


}

?>