<?php
namespace Framework\HTTP\Data;

interface PreVarInterface{
  public function get($key);
  public function set($key,$value);
}

?>