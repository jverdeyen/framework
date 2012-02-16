<?php
namespace Framework\Config;

interface ConfigInterface{
    
  public function set($key,$data);
  
  public function get($key);

}

?>