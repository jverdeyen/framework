<?php
namespace Framework\Config;

class AppConfig
{
    
  public function set($key,$data){
    $this->$key = $data;
  }
  
  public function get($key){
    return $this->$key;
  }

}

?>