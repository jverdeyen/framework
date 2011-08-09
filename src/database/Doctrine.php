<?php
namespace Framework\Database;

abstract class Doctrine{
  
  protected static $EntityManagers = NULL;

  abstract function createEm($type);
  abstract function getEm($type);
  abstract function getInstance($type);
  
}
?>