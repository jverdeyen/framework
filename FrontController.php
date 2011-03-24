<?php
namespace Lib;

class FrontController{

  protected $_controller;
  protected $_action;
  protected $_language;
  
  protected $_request;
  public static $_instance;
  
  
  public static function getInstance(){
		if( !(self::$_instance instanceof self))
			self::$_instance = new self();
		return self::$_instance;
	}
	
	private function __construct(){
	  Session::start();
	  
	  $this->_request = Request::getInstance();
    $this->_app = $this->_request->getApp();

	  if(!$this->_controller = Uri::getInstance()->getParam(1))
	    $this->_controller = DEFAULT_CONTROLLER;
	  
	  if(!$this->_action = Uri::getInstance()->getParam(2))
	    $this->_action = DEFAULT_ACTION;
	  
	  $this->setLanguage();
	  
	  setlocale(LC_ALL, $this->_language."_".strtoupper($this->_language).'.utf8');
    

	  if($this->_app == FRONTEND_APP_NAME)
	    self::redirectUrl();

	}
	
	public function setLanguage(){
	  $browser_lang = Localization::getBrowserLanguage();

	  if(Uri::getInstance()->getParam(0)){
	     // check in de url
	    $this->_language = Uri::getInstance()->getParam(0);
	  }elseif($this->_request->getCookie(COOKIE_NAME_LANGUAGE)){ 
	    // check de cookie
	    $this->_language = $this->_request->getCookie(COOKIE_NAME_LANGUAGE);
	  }elseif($browser_lang){
	    // check de browser language
	    $this->_language = $browser_lang;
	  }else{
	    $this->_language = DEFAULT_LANGUAGE;
	    // toon default
	  }
	  
	  if(in_array($this->_language,unserialize(LANGUAGES)))
      $this->_request->setCookie(COOKIE_NAME_LANGUAGE,$this->_language);
	}
	
	public function route(){
	  $controller_name = "\\".APP_NAME."\\App\\".ucfirst($this->_request->getApp())."\\Controller\\".ucfirst(strtolower($this->_request->getController()));
	  if(class_exists($controller_name)){
	    $controller = new $controller_name();
	    return $controller->init();
	  }
	   
	  throw new \Exception("Controller $controller_name could not be found.");
	}
	
	private function redirectUrl(){
    $uri =  Uri::getInstance();
    
    // zijn er meer dan 3 parameters (extra), doe dan zeker geen redirect (toch niet mogelijk)
    if($uri->getParam(3))
      return false;
    
    
    $param = array();
    $param[0] = $this->_language;
    $param[1] = $this->_controller;
    $param[2] = $this->_action;
    
    if($this->_action == DEFAULT_ACTION){
     unset($param[2]);
      if($this->_controller == DEFAULT_CONTROLLER){
        unset($param[1]);
      }  
    }
    
    if(!in_array($this->_language,unserialize(LANGUAGES))){
      // als de de taal niet bestaat, redirect dan met een 301 naar de default language
      Request::getInstance()->setLanguage(DEFAULT_LANGUAGE);
      Uri::redirect(array('controller' => 'index'),301);
      $param[0] = DEFAULT_LANGUAGE;
    }
    
    $redirect_suggestion = ROOT_URL.implode('/',$param);  
    if(substr($redirect_suggestion,-1) != '/')
      $redirect_suggestion .= '/';
    
    $current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
    if($current_url !=  $redirect_suggestion){
      header('Location: '.$redirect_suggestion,true,302);
      exit;
    }   
    

  }
	
}