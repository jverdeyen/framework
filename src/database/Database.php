<?php
namespace Framework\Database;

class Database extends \PDO{
  
  private static $connections = NULL; // a connection stack
  private static $current_connection_type = 'r';
  
  public static function getInstance($type = 'r'){
    return self::getConnection($type);
  }
  
  public static function getConnection($type = 'r'){
    if(!self::$connections[$type]){
      $database = self::getLogin($type);
      
      if(!is_array($database))
        throw new \Exception('Invalid database requested.');
        var_dump($database);
      self::$connections[$type] = new self('mysql:dbname='.$database['dbname'].';host='.$database['host'],
                                            $database['username'],
                                            $database['password'],
                                            array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '".$database['encoding']."'")
                                            );
                                            
      self::$connections[$type]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // debugging
      self::$current_connection_type = $type ;
    }
    return self::$connections[$type];
  }
  
  public function executeQuery($query){
    $rows = false;
    foreach(self::query($query) as $row){
      $rows[] = $row;
    }
    return $rows;
  }
  
  public function executeUpdate($query){
    $stm = self::prepare($query);
    return $stm->execute();
  }
  
  public function update($query,$params){
    $stm = self::prepare($query);
    return $stm->execute($params);
  }
  
  public function insert($query,$params){
    $connection = self::$connections[self::$current_connection_type];
    
    $stm = $connection::prepare($query);
    $stm->execute($params);
    
    return $connection::lastInsertId();
  }
  
  public function execute($query,$params = array()){
    return self::executeQuery($query);
  }

  
  public static function getLogin($type = 'r'){
    $yaml_config = ROOT_DIR.'config/database.yml';
    
    if(file_exists($yaml_config))
      $databases = \sfYaml::load(ROOT_DIR.'config/database.yml');
    else    
      $databases = unserialize(DATABASE);
      
    $database = $databases[$type];
    return $database;
  }
  
}
?>