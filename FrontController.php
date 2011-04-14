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
    $this->_controller  = $this->_request->getController();
    $this->_action  = $this->_request->getAction();
    $this->_language  = $this->_request->getLanguage();

	  setlocale(LC_ALL, $this->_language."_".strtoupper($this->_language).'.utf8');
    
    if( MULTI_LANGUAGE !== false ){
      if($this->_app == FRONTEND_APP_NAME)
  	    self::redirectUrlMultiLang();
    }else{
      if($this->_app == FRONTEND_APP_NAME)
  	    self::redirectUrlSingleLang();
    }
	  
    
	}
	
	public function route(){
	  $controller_name = "\\".APP_NAME."\\App\\".ucfirst($this->_request->getApp())."\\Controller\\".ucfirst(strtolower($this->_request->getController()));
	  if(class_exists($controller_name)){
	    $controller = new $controller_name();
	    return $controller->init();
	  }
	   
	  throw new \Exception("Controller $controller_name could not be found.");
	}
	
	private function redirectUrlSingleLang(){
	  $uri =  Uri::getInstance();
    
    // zijn er meer dan 2 parameters (extra), doe dan zeker geen redirect (toch niet mogelijk)
    if($uri->getParam(2)) return false;

    $param = array();
    $param[0] = $this->_controller;
    $param[1] = $this->_action;
    
    if($this->_action == DEFAULT_ACTION){
     unset($param[1]);
      if($this->_controller == DEFAULT_CONTROLLER){
        unset($param[0]);
      }  
    }
    
    $this->createUrlAndRedirect($param);    
	}
	
	private function redirectUrlMultiLang(){
    $uri =  Uri::getInstance();
    
    // zijn er meer dan 3 parameters (extra), doe dan zeker geen redirect (toch niet mogelijk)
    if($uri->getParam(3)) return false;
    
    
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
    
    $this->createUrlAndRedirect($param);
  }
  
  private function createUrlAndRedirect($param){
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