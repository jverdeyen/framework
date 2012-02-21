<?php
namespace Framework\Controller;
use Framework\Exception\ControllerNotFoundException;

class FrontController{

  protected $_controller;
  protected $_action;
  protected $_language;
  protected $_app;
  protected $Request;
  
  private $Router;
  
	
	public function __construct(Request $Request){
	  Session::start();
	  
	  $caching = true;
	  if(ENVIRONMENT == 'dev')
	    $caching = false;
	  
	  $this->Request = $Request;
	  $this->Router = new Router\Router($Request);
	  $this->Router->setCaching(new \Framework\Cache\Apc());
	  
	  $this->Router->route();
	  
	  $this->_app = $this->Request->getApp();
    $this->_controller  = $this->Request->getController();
    $this->_action  = $this->Request->getAction();
    $this->_language  = $this->Request->getLanguage();
	  
	  setlocale(LC_ALL, $this->_language."_".strtoupper($this->_language).'.utf8');

    if(!$this->Router->foundMapping()){
      if( MULTI_LANGUAGE !== false ){
        if($this->_app['clean_url'] === true)
    	    $this->redirectUrlMultiLang();
      }else{
        if($this->_app['clean_url'] === true)
    	    $this->redirectUrlSingleLang();
      }
    }
    
	}
	
	public function route(){
	  $controller_name = "\\".APP_NAME."\\App\\".ucfirst($this->_app['name'])."\\Controller\\".ucfirst(strtolower($this->_controller));

	  if(class_exists($controller_name)){
	    $controller = new $controller_name($this->Request);
	    return $controller->init();
	  }   
	  
	  throw new ControllerNotFoundException("Controller $controller_name could not be found.");
	}
	
	private function redirectUrlSingleLang(){    
    // zijn er meer dan 2 parameters (extra), doe dan zeker geen redirect (toch niet mogelijk)
    if($this->Request->getParam(2)) return false;

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
    
    // zijn er meer dan 3 parameters (extra), doe dan zeker geen redirect (toch niet mogelijk)
    if($this->Request->getParam(3)) return false;
    
    
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
      $this->Request->setLanguage(DEFAULT_LANGUAGE);
      Uri::redirect(array('controller' => 'index'),301);
      $param[0] = DEFAULT_LANGUAGE;
    }
    
    $this->createUrlAndRedirect($param);
  }
  
  private function createUrlAndRedirect($param){
    $redirect_suggestion = $this->_app['url'].implode('/',$param);  
    if(substr($redirect_suggestion,-1) != '/')
      $redirect_suggestion .= '/';
    
    $current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            
    if($current_url !=  $redirect_suggestion){
      header('Location: '.$redirect_suggestion,true,302);
      exit;
    }
  }
	
}