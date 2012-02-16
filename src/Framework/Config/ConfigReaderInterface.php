<?php
namespace Framework\Config;

interface ConfigReaderInterface{
    
  public function getConfigArray();
  
  public function add($source);
  
}

?>