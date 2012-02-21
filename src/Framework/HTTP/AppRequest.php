<?php 
namespace Framework\HTTP;

class AppRequest implements RequestInterface{
  
  public $Request;
  public $Config;
  
  public $app;
  public $controller;
  public $action;
  public $language;
  
  public function __construct(Request $Request = null,\Framework\Config\Config $Config = null)
  {
    $this->Request = $Request;
    $this->Config = $Config;
  }
  
  public function initApp()
  {
    $this->app = $this->determineAppKey();
  }
  
  public function determineAppKey(Server $Server = null, \Framework\Config\ConfigInterface $Config = null)
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
  
  
  
  public function determineController(){
    $controller_param_index = 0;
    $controller = $this->Config->get('apps.'.$this->app.'.default_controller');
    
    if($this->Config->get('apps.'.$this->app.'.multi_language')){
      $controller_param_index = 1;
    }
    
    if($this->Request->getParam($controller_param_index)){
      $controller = $this->Request->getParam($controller_param_index);
    }
    
    return $controller;
  }
  
  public function determineAction(){
    $action_param_index = 1;
    $action = $this->Config->get('apps.'.$this->app.'.default_action');
    
    if($this->Config->get('apps.'.$this->app.'.multi_language')){
      $action_param_index = 2;
    }
    
    if($this->Request->getParam($action_param_index)){
      $action = $this->Request->getParam($action_param_index);
    }
    
    return $action;
  }
  

  /*
  private function initAction(){
    if(MULTI_LANGUAGE === false){
	    if(!$this->action = $this->getParam(1))
  	    $this->action = DEFAULT_ACTION;
	  }else{
	    if(!$this->action = $this->getParam(2))
  	    $this->action = DEFAULT_ACTION;
	  }
  }
  
  private function initLanguage(){
    if(MULTI_LANGUAGE === false){
	    $this->language = DEFAULT_LANGUAGE;
      return true;
    }
	  
	  $browser_lang = Localization::getBrowserLanguage();

	  if($this->getParam(0)){
	     // check in de url
	    $this->language = $this->getParam(0);
	  }elseif(self::getCookie($this->getAppLanguageCookieName())){ 
	    // check de cookie
	    $this->language = self::getCookie($this->getAppLanguageCookieName());
	  }elseif($browser_lang){
	    // check de browser language
	    $this->language = $browser_lang;
	  }else{
	    $this->language = DEFAULT_LANGUAGE;
	    // toon default
	  }
	  
	  if(in_array($this->language,unserialize(LANGUAGES)))
      self::setCookie($this->getAppLanguageCookieName(),$this->language);
  }
  
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
	
	private function initParams(){
	  $params =  explode('/', $_SERVER['QUERY_STRING']);
  	if(end($params) == ''){
  	  array_pop($params);
  	}
  	$this->params = $params;
	}
 
 
  
  
  public function getApp(){

    if(!isset($this->app)){
      list($subdomain, $rest) = explode('.', $this->getServer('SERVER_NAME'), 2);
      $apps = unserialize(APPS);
      
      foreach($apps as $key => $app){
        if( strpos($subdomain,$key) !== false){
          $this->app = $app;
          break;
        }
      }   
      
      if(!is_array($this->app))
        $this->app = $apps['default'];
      
      if(!is_array($this->app))
        throw new \Exception('No default Application name defined!');
      
   }
   return $this->app;
  }
  
  public function setApp($name){
    $apps = unserialize(APPS);
    foreach($apps as $key => $app){
      if($app['name'] == $name){
        $this->_app = $app;
        return true;
      } 
    }
    throw new \Exception('The application name can not be found: '.$name);
  }
  
  public function getAppName(){
    $this->_app = $this->getApp();
    return $this->_app['name'];
  }
  
  public function setAppName($x){
    return $this->setApp($x);
  }
  
  
  public function getAppLanguageCookieName(){
    $this->_app = $this->getApp();
    if($this->_app['cookie_name_language'] != ''){
      return $this->_app['cookie_name_language'];
    }
    
    return $this->_app['name']."_language_cookie";
  }
   
   public function setServer($name,$value){
     $_SERVER[$name] = $value;
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