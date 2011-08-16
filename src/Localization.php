<?php

namespace Framework;


class Localization {
  
  private static $_instance = null;
  private function __construct() {}
  

  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  /**
   * Get the ip address of the visitor.
   */
  public function getIp() {
    if($_SERVER['HTTP_X_FORWARD_FOR']) {
      $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
    }
    else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    if(strstr($ip, ',')) {
      $ips = explode(', ', $ip);
      $ip = $ips[0];
    }
    return trim($ip);
  }
  
  public function getHost(){
    if(ENVIRONMENT == 'dev'){
      return 'purk';
    }
    return gethostbyaddr(self::getIp());
  }
  
  public function getBrowserLanguage($supported_languages=array()) {
    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $languages = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
      //$languages = 'fr-ch;q=0.3, da, en-us;q=0.8, en;q=0.5, fr;q=0.3';
      //need to remove spaces from strings to avoid error
      $languages = str_replace(' ', '', $languages);
      $languages = explode(',', $languages);
      foreach($languages as $language_part) {
        $lang = strtoupper(substr($language_part, 0, 2));
        if(strlen($lang) == 2) {
            return strtolower($lang);
          }
      }
    }
    return false;
  }
  
}
