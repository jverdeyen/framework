<?php
namespace Framework\HTTP;

interface PreVarInterface{
  public function get($key);
  public function set($key,$value);
}

?>