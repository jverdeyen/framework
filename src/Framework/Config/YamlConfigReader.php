<?php
namespace Framework\Config;

Class YamlConfigReader implements ConfigReaderInterface{
  
  public $config;
    
  public function __construct($file = false)
  {
    if($file !== false){
      $this->config = $this->getFileContent($file);
    }
    
  }
  
  public function getConfigArray()
  {
    return $this->config;
  }
  
  public function add($source)
  {
    $config = $this->getFileContent($source);
    
    if(is_array($config) && is_array($this->config) ){
      $this->config = array_merge($config,$this->config);
    }
    elseif(!is_array($this->config)){
      $this->config = $config;
    }
  }

  public function getFileContent($file)
  {
    if(file_exists($file)){
      return \Symfony\Component\Yaml\Yaml::parse($file);
    }
    throw new \Exception('Config file could not be loaded: '.$file);
  }

}

?>