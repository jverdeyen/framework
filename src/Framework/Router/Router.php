<?php
namespace Framework\Router;

class Router implements RouterInterface{
  
  public $AppRequest = null;
  public $Config = null;
  public $Cache = null;
  
  private $Routes = array();
  private $MatchedMapping = false;
      
  public function __construct(  \Framework\HTTP\RequestInterface $AppRequest,
                                \Framework\Cache\CacheInterface $Cache = null,
                                \Framework\Config\Config $Config = null)
  {
    
    if($Config == null){
      $this->Config = $AppRequest->Config;
    }
    else{
      $this->Config  = $Config;
    }
    
    $this->AppRequest = $AppRequest;
    $this->Cache = $Cache;
  }
  
  public function setCache(\Framework\Cache\CacheInterface $Cache)
  {
    $this->Cache = $Cache;
  }
  

  public function route()
  {
    //$this->AppRequest->initApp();
    $this->Routes = $this->getRoutes();
    
    if(count($this->Routes) <= 0){
      return false;
    }

    $Route = $this->findMatchingRoute($this->Routes, $this->AppRequest->Request);

    if($Route != false){
      //$this->MatchedMapping = $Mapping;
      $this->AppRequest->setController($Route->getController());
      $this->AppRequest->setAction($Route->getAction());
      $this->AppRequest->setApp($Route->getApp());
      //$this->AppRequest->setExtraParams($this->Request);
    }
    
  }
  
  public function getMatchingRouteForLink(\Framework\Router\Link $Link, $Routes = null)
  {
    if($Routes == null){
      $Routes = $this->getRoutes();
    }
    

    foreach($Routes as $Route)
    {
      $result = $this->getMatchFromLink($Link, $Route);
      
      if($result != false){
        return $result;
      }
      else{
        continue;
      }
    }
    
    return false;

  }
  
  public function findMatchingRoute($Routes, \Framework\HTTP\RequestInterface $Request)
  {
    
    $route_cache_key = 'RoutesMatch'.md5(implode('.',$Request->getParams()).$this->AppRequest->app);
    
    if($this->Caching != null){
      if($Route = $this->Caching->getData($route_cache_key)){
        return $Mapping;
      }
    }
    
    
    foreach($this->Routes as $Route){
      if($CheckedRoute = $this->compareRouteRequest(clone $Route, $Request)){
        if($this->Caching != null){
          $this->Caching->setData($route_cache_key,$Route);
        }
        return $CheckedRoute;
      }
        
    }
    return false;

  }
  
  public function getMatchFromLink(\Framework\Router\Link $Link, \Framework\Router\Route $Route)
  {
    // TODO make this multi language work
    $url_slugs = $Link->getParams();
    $index_start = 0;
    $reserved_count = 2;
    $index = $index_start;
    $total_extra =  count($url_slugs) <= $reserved_count ? 0 : count($url_slugs)  - $reserved_count;
    
    var_dump($Route);
    if($url_slugs[$index] != $Route->getController() && $Route->getController() != '*' ){
      return false;
    }
      
    $index++;
    
    if($Mapping->getAction() != '*'){
      if($url[$index] != $Mapping->getAction() && $url[$index] != null)
        return false;       
      if($url[$index] == null && $Mapping->getAction() != 'index')
        return false;
    }
 
    $index += 2;
    $extras = $Mapping->getExtra();
    $extras_without_reserved_words = $extras;
    
    foreach($this->getReservedWords() as $word){
      unset($extras_without_reserved_words[$word]);
    }
    $extra_count = count($extras_without_reserved_words);

    if($extra_count != $total_extra)
      return false;
       
    $replace = array();
    $replace_by = array(); 
    foreach($extras as $key => $value){  
      $regex = $Mapping->getSlugMatch($key);
     
      if($regex == '*' || preg_match($regex,$url[$index])){
        $replace[] = '{'.$key.'}';
        
        if($key == 'controller')
          $replace_by[] = $url[$index_start];
        elseif($key == 'action')
          $replace_by[] = $url[$index_start+1];
        else
          $replace_by[] = $url[$index];
          
        $index++;
        continue;
      }

      return false;         
    }
    return true;
    return str_replace($replace,$replace_by,$Mapping->getPattern());
  }
  
  public function compareRouteRequest(\Framework\Router\Route $Route, \Framework\HTTP\RequestInterface $Request)
  {
    $route_slugs = $Route->getPatternArray();
    $request_params = $Request->getParams();
    $reserved_words = $this->Config->get('mapping.reserved_words');
    
    if(count($parts) != count($request_uri)){
      return false;
    }
      

    foreach($route_slugs as $key => $slug)
    {
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        
        $slug_key = substr($slug, 1, -1);
        $regex = $Route->getSlugMatch($slug_key);

        if(in_array($slug_key,$reserved_words)){
          
          if($slug_key == 'controller' && ($Route->getController() == '*' || preg_match($Route->getController(),$request_params[$key])) ){
            $Route->setController($request_params[$key]);
            continue;
          }
          
          if($slug_key == 'action' && ($Route->getAction() == '*' || preg_match($Route->getAction(),$request_params[$key])) ){
            $Route->setAction($request_params[$key]);
            continue;
          }
              
        }
        
        // als het geen wildcard is en het match niet OF het is een reserved word -> geen match
        if( in_array($slug_key,$reserved_words) || !( $regex == '*' || preg_match($regex,$request_params[$key]))){
          return false;
        }
        else{
          $this->AppRequest->$slug_key = $request_params[$key];
        }
          
      }
      else{
        // easy literal match
        if(trim($slug) != trim($request_params[$key])){
          return false;
        }
          
      }
      
    }
    
    return $Route;
    
  }
    
  public function getRoutes()
  {
    if($this->Caching != null){
      
      $cache_key = 'RoutesYaml'.$this->AppRequest->app;
      
      if(!($this->Routes = $this->Caching->getData($cache_key))){
        $Routes = $this->getRoutesFromConfig();
        $this->Caching->setData($cache_key,$Routes);
        return $Routes;
      }            
    } 
    else{
      return $this->getRoutesFromConfig();      
    }    
  }
  
  public function getRoutesFromConfig(\Framework\Config\Config $Config = null)
  {
    if($Config == null){
      $Config = $this->Config;
    }
      
    $routesArray = $Config->get('mapping.'.$Config->get('apps.'.$this->AppRequest->app.'.name'));

    if(!is_array($routesArray)){
      return false;
    }
    
    $Routes = array();
    
    foreach($routesArray as $key => $value){  
      $Route = new Route($key,$value,$Config->get('mapping.reserved_words'));
      $Routes[] = $Route;
    }
    
    return $Routes;
  }

  
  
}
?>