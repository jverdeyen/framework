<?php
namespace Lib;

class Uri{

	public static $params = array();
	private static $instance = null;

	public static function getInstance(){
 		if(is_null(self::$instance))
 			self::$instance = new uri;
		return self::$instance;
	}


	private function __construct(){
		self::$params =  explode('/', $_SERVER['QUERY_STRING']);
	}

	public function getParam($key){
		if(array_key_exists($key, self::$params))
			return preg_replace('/[^A-Za-z0-9\-._]/','',urldecode(self::$params[$key]));
		return false;
	}
	
	public function getExtraParams(){
	  $total = count(self::$params);
	  if($total <= 3)
	    return false;
	  else
	    return array_slice(self::$params, 3, $total-2);
	}
	
	private static function constructUrl($params,$url){
	  if(is_array($params['extra'])){
	    $i = 3;
	    foreach($params['extra'] as $key => $value){
  	    $url[$i] = strtolower(str_replace(' ','-',$value)); 
  	    $url[$i] = GFunctions::replaceAccents($url[$i]);
  	    $url[$i] = preg_replace('/[^A-Za-z0-9\-._]/', '', $url[$i]);
  	    $i++;
  	  }
	  }

    $apps = unserialize(APPS);
    
    foreach($apps as $key => $app){
      if( strtolower(Request::getInstance()->getApp()) == strtolower($app['name'])){
        break;;
      }
    }
        
    if(trim($app['url']) == ''){
      $url = $apps['default']['url'].implode('/',$url);;
    }else{
      $url = $app['url'].implode('/',$url);;
    }

	  if(substr($url,-1) != '/')
      $url .= '/';
      
    return $url;
	}
	
	private static function getUrlSingleLanguage($params){
	  $request = Request::getInstance();
	  
    $url = array();
	  $url[0] = 'index'; // controller
	  $url[1] = 'index'; // action
	  
	  
	  if($params['controller'] != '')
	    $url[0] = $params['controller'];
	  if($params['action'] != '')
	    $url[1] = $params['action'];
	  
	  if($url[1] == DEFAULT_ACTION && !is_array($params['extra'])){
      unset($url[1]);
      if($url[0] == DEFAULT_CONTROLLER){
        unset($url[0]);
      }  
    }
	  
	  return self::constructUrl($params,$url);
	}
	
	private static function getUrlMultiLanguage($params){
	  $request = Request::getInstance();
	  $language = $request->getLanguage();
	  
    $url = array();
    $url[0] = isset( $language ) ? $language : DEFAULT_LANGUAGE; // language
	  $url[1] = 'index'; // controller
	  $url[2] = 'index'; // action
	  
	  
	  if($params['controller'] != '')
	    $url[1] = $params['controller'];
	  if($params['action'] != '')
	    $url[2] = $params['action'];
	  if($params['language'] != '')
	    $url[0] = $params['language'];
	  
	  if($url[2] == DEFAULT_ACTION && !is_array($params['extra'])){
      unset($url[2]);
      if($url[1] == DEFAULT_CONTROLLER){
        unset($url[1]);
      }  
    }
	  
	  return self::constructUrl($params,$url);
	}
	
	public static function getUrl($params){
	  if(MULTI_LANGUAGE === false){
	    return self::getUrlSingleLanguage($params);
	  }
	  
	  return self::getUrlMultiLanguage($params);
	}
	
	public static function redirect($params,$http_response_code = '302'){
	  $url = self::getUrl($params);
	  header('Location: '.$url,true,$http_response_code);
	  exit;
	}
 
	private function __clone(){}

}
