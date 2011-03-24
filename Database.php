<?php
namespace Lib;

class Database{

  private static $instance = NULL;
  
  private function __construct(){}
  private function __clone(){}
  /**
   * Geeft de database connectie terug
   *
   * @return Database
   * @author Joeri Verdeyen
   **/
  public static function getInstance(){
    if(!self::$instance)
      self::$instance = new DatabaseHandler(DB_SERVER,DB_NAME,DB_USERNAME,DB_PASSWORD);
    return self::$instance;    
  }
  
}

?>