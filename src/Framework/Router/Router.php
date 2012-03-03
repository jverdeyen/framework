<?php
namespace Framework\Router;

class Router extends RouteReader implements RouterInterface {
  
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
      
}
?>