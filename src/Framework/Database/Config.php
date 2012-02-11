<?php
namespace Framework\Database;

class Config{
  
  private static $databases = NULL; // a connection stack
  
  private function __construct(){}
  
  public static function getConfig($type = 'r'){    
    $yaml_config = ROOT_DIR.'config/database.yml';
    
    if(is_array(self::$databases))
      return self::$databases[$type];
    
    if(file_exists($yaml_config))
      $databases = \Symfony\Component\Yaml\Yaml::parse(ROOT_DIR.'config/database.yml');
    else    
      $databases = unserialize(DATABASE);
      
    $database = $databases[$type];
    return $database;
  }
  
  
}
?>