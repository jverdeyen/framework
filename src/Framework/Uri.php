<?php
namespace Framework;

class Uri{

  public $Request;
	public static $params = array();

	public function __construct(Request $Request){
	  self::$params = $Request->getParams();		
		$this->Request = $Request;
	}
	
	
	public function getParams(){
	  return self::$params;
	}

	public function getParam($key){
		if(array_key_exists($key, self::$params))
			return preg_replace('/[^A-Za-z0-9\-._]/','',urldecode(self::$params[$key]));
		return false;
	}
	
	public function getExtraParams(){
	  $total = count(self::$params);
	  if(MULTI_LANGUAGE === false){
	    if($total <= 2) 
	      return false;
	    return array_slice(self::$params, 2, $total-1);
    }else{
	    if($total <= 3) 
	      return false;
	    return array_slice(self::$params, 3, $total-2);
    }
	}
	
	private function constructUrl($params,$url,$app_name){
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
      if($app_name === false){
        if( strtolower($this->Request->getAppName()) == strtolower($app['name']))
          break;
      }else{
        if( strtolower($app_name) == strtolower($app['name']))
          break;
      }
      
    }

    $Router = new \Framework\Router\Router($this->Request);
    $url_parts = $Router->findAUrlMapping($url);
    
    if($url_parts != false)
      return $app['url'].substr($url_parts,1);
    
    if(trim($app['url']) == ''){
      $url = $apps['default']['url'].implode('/',$url);;
    }else{
      $url = $app['url'].implode('/',$url);;
    }

	  if(substr($url,-1) != '/')
      $url .= '/';
      
    return $url;
	}
	
	private function getUrlSingleLanguage($params,$app_name = false,$cleanup = true){	  
    $url = array();
	  $url[0] = 'index'; // controller
	  $url[1] = 'index'; // action
	  
	  
	  if($params['controller'] != '')
	    $url[0] = $params['controller'];
	  if($params['action'] != '')
	    $url[1] = $params['action'];
	  
	  if($url[1] == DEFAULT_ACTION && !is_array($params['extra']) && $cleanup === true){
      unset($url[1]);
      if($url[0] == DEFAULT_CONTROLLER){
        unset($url[0]);
      }  
    }
	  return $this->constructUrl($params,$url,$app_name);
	}
	
	private function getUrlMultiLanguage($params,$app_name = false,$cleanup = true){
	  $language = $this->Request->getLanguage();
	  
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
	  
	  if($url[2] == DEFAULT_ACTION && !is_array($params['extra']) && $cleanup === true){
      unset($url[2]);
      if($url[1] == DEFAULT_CONTROLLER){
        unset($url[1]);
      }  
    }
	  
	  return $this->constructUrl($params,$url,$app_name);
	}
	
	public function getUrl($params,$app_name = false,$cleanup = true){
	  if(MULTI_LANGUAGE === false){
	    return $this->getUrlSingleLanguage($params,$app_name,$cleanup);
	  }
	  
	  return $this->getUrlMultiLanguage($params,$app_name,$cleanup);
	}
	
	public function redirect($params,$http_response_code = '302'){
	  
	  if($http_response_code == false)
	    $http_response_code = '302';
	  
	  $url = self::getUrl($params);
	  header('Location: '.$url,true,$http_response_code);
	  exit;
	}
 
	private function __clone(){}

}
