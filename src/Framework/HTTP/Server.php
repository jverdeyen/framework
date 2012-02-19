<?php
namespace Framework\HTTP;

Class Server implements PreVarInterface{
  
  public $server;
  
  public function __construct($server = null)
  {
    if($server != null){
       $this->server = $server;
    }
    else{
      $this->server = $_SERVER;
    }
  }


  public function get($key){
    return $this->server[$key];
  }
  
  public function set($key,$value){
    return $this->server[$key] = $value;
  }

}

?>