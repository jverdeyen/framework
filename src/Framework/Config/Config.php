<?php
namespace Framework\Config;

Class Config implements ConfigInterface{
  
  public static $configuration;  
  public $Cache;
  
  public function __construct(ConfigReaderInterface $ConfigReader,CacheInterface $Cache = null)
  {
    $this->configuration = $ConfigReader->getConfigArray();
    $this->Cache = $Cache;
  }
  
  public function set($key,$data)
  {
    $this->configuration[$key] = $data;
  }
  
  public function get($key)
  {
    if(strpos($key,'.') >= 1){
      $value = $this->getDeepKey($key);
    }else{
      $value =  $this->configuration[$key];
    }

    if(substr($value, 0,1) == "@"){
      $value = $this->get(substr($value,1));
    }
    
    $value = $this->replacePlaceholder($value); 
        
    return $value;
  }
  
  public function replacePlaceholder($value, $pattern = "/\%([a-z]+(.[a-z]+)*)\%/" ){
    preg_match_all($pattern, $value, $matches);
    
    if(count($matches[0]) <= 0)
      return $value;
    
    for ($i=0, $c=count($matches[0]); $i < $c; $i++) {
    	$value = str_replace($matches[0][$i],$this->get($matches[1][$i]),$value);
    }
    
    return $value;
  }
  
  public function getDeepKey($key){
    $keys = explode('.',$key);
    $return = $this->configuration[$keys[0]];
    for($i=1;$i<count($keys);$i++){
      $return = $return[$keys[$i]];
    }
    
    return $return;
  }

}

?>