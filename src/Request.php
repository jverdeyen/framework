<?php 
namespace Framework;

class Request {
 
  private static $_instance = null;
 
  private $_controller;
  private $_action;
  private $_language;
  private $_app;
 
  public function __construct(){
    $uri = Uri::getInstance();
   
    $this->_app = self::getApp();
    
    self::initController();
    self::initAction();
    self::initLanguage();

  }
 
  public static function getInstance(){
    if(!isset(self::$_instance))
      self::$_instance = new self();
    return self::$_instance;
  }
  
  private function initController(){
    if(MULTI_LANGUAGE === false){
	    if(!$this->_controller = Uri::getInstance()->getParam(0))
  	    $this->_controller = DEFAULT_CONTROLLER;
	  }else{
	    if(!$this->_controller = Uri::getInstance()->getParam(1))
  	    $this->_controller = DEFAULT_CONTROLLER;
	  }
  }
  
  private function initAction(){
    if(MULTI_LANGUAGE === false){
	    if(!$this->_action = Uri::getInstance()->getParam(1))
  	    $this->_action = DEFAULT_ACTION;
	  }else{
	    if(!$this->_action = Uri::getInstance()->getParam(2))
  	    $this->_action = DEFAULT_ACTION;
	  }
  }
  
  private function initLanguage(){
    if(MULTI_LANGUAGE === false){
	    $this->_language = DEFAULT_LANGUAGE;
      return true;
    }
	  
	  $browser_lang = Localization::getBrowserLanguage();

	  if(Uri::getInstance()->getParam(0)){
	     // check in de url
	    $this->_language = Uri::getInstance()->getParam(0);
	  }elseif(self::getCookie(COOKIE_NAME_LANGUAGE)){ 
	    // check de cookie
	    $this->_language = self::getCookie(COOKIE_NAME_LANGUAGE);
	  }elseif($browser_lang){
	    // check de browser language
	    $this->_language = $browser_lang;
	  }else{
	    $this->_language = DEFAULT_LANGUAGE;
	    // toon default
	  }
	  
	  if(in_array($this->_language,unserialize(LANGUAGES)))
      self::setCookie(COOKIE_NAME_LANGUAGE,$this->_language);
  }
 
  public function getController(){
    return $this->_controller;
  }
 
  public function getAction(){
    return $this->_action;
  }
 
  public function getLanguage(){
    return $this->_language;
  }
  
  public function setLanguage($language){
    $this->_language = $language;
  }
 
  public function getApp(){

    if(!isset($this->_app)){
      list($subdomain, $rest) = explode('.', $this->getServer('SERVER_NAME'), 2);
      $apps = unserialize(APPS);
      
      foreach($apps as $key => $app){
        if( strpos($subdomain,$key) !== false){
          $this->_app = $app['name'];
          break;
        }
      }   
      
      if(trim($this->_app) == '')
        $this->_app = $apps['default']['name'];
      
      if(trim($this->_app) == ''){
        throw new \Exception('No default Application name defined!');
      }
   }
   return $this->_app;
  }
 
 
   public function getServer($name) {
     return stripslashes($_SERVER[$name]);
   }
   
   public function setServer($name,$value){
     $_SERVER[$name] = $value;
   }
 
   public function getPost($name = false) {
     if(is_array($_POST[$name])){
       foreach($_POST[$name] as $key => $value){
         $_POST[$name][$key] = stripslashes($value);
       }
       return $_POST[$name];
     }
     return stripslashes($_POST[$name]);
   }
 
   public function issetPost($name) {
     return isset($_POST[$name]);
   }
   
   public function setPost($name,$value){
     $_POST[$name] = $value;
    }
 
   public function unsetPost($name) {
     unset($_POST[$name]);
   }
  
   public function setGet($name,$value){
     $_GET[$name] = $value;
   }
   public function getGet($name) {
     return stripslashes($_GET[$name]);
   }
 
   public function issetGet($name) {
     return isset($_GET[$name]);
   }
 
   public function unsetGet($name) {
     unset($_GET[$name]);
   }
 
   public function getCookie($name) {
     return stripslashes($_COOKIE[$name]);
   }
   
   public function setCookie($name,$value) {
    setcookie($name,$value,time() + (60*60*24*60),'/');
   }
 
   public function isPost() {
     return ('POST' == $this->getServer('REQUEST_METHOD'));
   }
 
   public function isGet() {
     return ('GET' == $this->getServer('REQUEST_METHOD'));
   }
 
 
}