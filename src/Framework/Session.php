<?php 
namespace Framework;

class Session {
 
  private static $instance;
  
  public static function getInstance() {
    if(!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  
  public function start($name='') {
    if($name != '') {
      session_name ($name);
    }
    session_start();
  }
  
  public function get($name) {
    return $_SESSION[$name];
  }
  
  public function set($name, $value) {
    $_SESSION[$name] = $value;
  }
  
  public function issetVar($name) {
    return isset($_SESSION[$name]);
  }
  
  public function unsetVar($name) {
    unset($_SESSION[$name]);
  }
  
  public function getSessionId(){
    return session_id();
  }
  
}