<?php
namespace Framework\Config;

Class YamlConfigReader implements ConfigReaderInterface{
  
  public static $config;
    
  public function __construct($file){
    $this->config = $this->getFileContent($file);
  }
  
  public function getConfigArray(){
    return $this->config;
  }
  
  
  public function add($source){
    $config = $this->getFileContent($source);
    
    if(is_array($config)){
      $this->config = array_merge($config,$this->config);
    }
  }

  public function getFileContent($file){
    if(file_exists($file)){
      return \Symfony\Component\Yaml\Yaml::parse($file);
    }
    throw new \Exception('Config file could not be loaded: '.$file);
  }

}

?>