<?php 
namespace Framework\HTTP;

class AppRequest implements RequestInterface{
  
  public $Request;
  public $Config;
  
  public $app;
  public $controller;
  public $action;
  public $language;
  
  public function __construct(Request $Request = null, \Framework\Config\Config $Config = null)
  {
    $this->Request = $Request;
    $this->Config = $Config;
  }
  
  public function initAll(){
    $this->initApp();
    $this->initController();
    $this->initAction();
    $this->initLanguage();
  }
  
  public function initApp()
  {
    $this->app = $this->findAppKey();
  }
  
  public function initController()
  {
    $this->controller = $this->findController();
  }
  
  public function initAction()
  {
    $this->action = $this->findAction();
  }
  
  public function initLanguage()
  {
    $this->language = $this->findLanguage();
  }
  
  public function findAppKey(Server $Server = null, \Framework\Config\ConfigInterface $Config = null)
  {
    if($Config == null){
      $Config = $this->Config;
    }
    
    if($Server == null){
      $Server = $this->Request->getServer();
    }
    
    list($subdomain, $rest) = explode('.',$Server->get('SERVER_NAME'),2);
    $apps = $Config->get('apps');
    $detected_app = false;
    
    foreach($apps as $key => $app){
      if(strpos($subdomain, $key) !== false){
        $detected_app = $key;
        break;
      }
    }
    
    if($detected_app === false){
      $detected_app = $Config->get('apps.default');
    }
  
    return $detected_app;
  }
    
  public function findController()
  {
    $controller_param_index = 0;
    $controller = $this->Config->get('apps.'.$this->app.'.defaults.controller');
    
    if($this->Config->get('apps.'.$this->app.'.multi_language')){
      $controller_param_index = 1;
    }
    
    if($this->Request->getParam($controller_param_index)){
      $controller = $this->Request->getParam($controller_param_index);
    }
    
    return $controller;
  }
  
  public function findAction()
  {
    $action_param_index = 1;
    $action = $this->Config->get('apps.'.$this->app.'.defaults.action');
    
    if($this->Config->get('apps.'.$this->app.'.multi_language')){
      $action_param_index = 2;
    }
    
    if($this->Request->getParam($action_param_index)){
      $action = $this->Request->getParam($action_param_index);
    }
    
    return $action;
  }
  
  public function findLanguage()
  {
    if($this->Config->get('apps.'.$this->app.'.multi_language') == false){
      return $this->Config->get('apps.'.$this->app.'.defaults.language');
    }
    
    /**
     * 1. Check url language
     * 2. Check cookie language
     * 3. Check browser language
     * 4. Use default language
     */
     if(in_array($this->Request->getParam(0),$this->Config->get('apps.'.$this->app.'.languages'))){
       return $this->Request->getParam(0);
     }
     
     if(in_array($this->Request->getCookie($this->Config->get('apps.'.$this->app.'.cookie.language')),$this->Config->get('apps.'.$this->app.'.languages'))){
       return $this->Request->getCookie($this->Config->get('apps.'.$this->app.'.cookie.language'));
     }
     
     //TODO retrieve browser language
     
     return $this->Config->get('apps.'.$this->app.'.defaults.language');
  }
  
  public function getServer()
  {
    return $this->Server();
  }
  
  public function getApp() { return $this->app; } 
  public function getController() { return $this->controller; } 
  public function getAction() { return $this->action; } 
  public function getLanguage() { return $this->language; } 
  public function setApp($x) { $this->app = $x; } 
  public function setController($x) { $this->controller = $x; } 
  public function setAction($x) { $this->action = $x; } 
  public function setLanguage($x) { $this->language = $x; } 
  
  
  /*
  private function initExtraParams(){
    $params = $this->getParams();
	  $total = count($params);
	  if(MULTI_LANGUAGE === false){
	    if($total > 2) 
	      $this->extra_params = array_slice($params, 2, $total-1);
	    
    }else{
	    if($total > 3) 
	      $this->extra_params = array_slice($params, 3, $total-2);
    }
	}

 
   public function getPost($name = false) {
     if(is_array($_POST[$name])){
       foreach($_POST[$name] as $key => $value){
         if(is_array($value)){
           foreach($_POST[$name][$key] as $key2 => $value2){
             $_POST[$name][$key][$key2] = stripslashes($value2);
           }
         }else{
           $_POST[$name][$key] = stripslashes($value);
         }
       }
       return $_POST[$name];
     }

     return stripslashes($_POST[$name]);
   }
   
   public function getPostArray(){
    foreach($_POST as $key => $value){
      $_POST[$key] = self::getPost($key);
    }
    
    return $_POST;
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
 */
}