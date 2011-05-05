<?php
namespace Framework;

class Loader {
  
  private static $facebook;

  public function __construct() {}
  
  static public function getFacebook($options) {
    if(!self::$facebook) {
      require_once(dirname(__FILE__).'/vendor/Facebook/facebook.php');
      self::$facebook = new \Facebook($options);
    }
    return self::$facebook;
  }
  

}