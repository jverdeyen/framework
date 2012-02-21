<?php
namespace Framework\HTTP\Data;

Class PreVar implements PreVarInterface{
  
  public $array;
  public static $name;
  
  public function __construct($array = null)
  {
    if($server != null){
       $this->array = $array;
    }
    else{
      $this->array = $$name;
    }
  }


  public function get($key)
  {
    return $this->array[$key];
  }
  
  public function set($key,$value)
  {
    return $this->array[$key] = $value;
  }
  
  public function getArray()
  {
    $array = array();
    foreach($this->$array as $key => $value){
      $array[$key] = stripslashes($value);
    }
    
    return $array;
   }

}

?>