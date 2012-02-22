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
  
  /**
   * Converts the request object into a matching mapping
   */
  public function route(){
    //$this->AppRequest->initApp();
    $this->Routes = $this->getRoutes();
    
    if(count($this->Routes) <= 0){
      return false;
    }

    $Route = $this->findMatchingRoute($this->Routes, $this->AppRequest->Request);
    /*
    if($Mapping != false){
      $this->MatchedMapping = $Mapping;
      $this->Request->setController($Mapping->getController());
      $this->Request->setAction($Mapping->getAction());
      $this->Request->setApp($Mapping->getApp());
      $this->Request->setExtraParams($this->Request);
    }
    */
  }
  
  public function findAUrlMapping($uri){
    $this->readRoutes();
    foreach($this->Routes as $Mapping){
      $result = $this->checkMatchFromUrl($Mapping,$uri);
      if($result != false){
        return $result;
      }else{
        continue;
      }
    }
    
    return false;

  }
  
  public function findMatchingRoute($Routes, \Framework\HTTP\RequestInterface $Request)
  {
    
    $route_cache_key = 'RoutesMatch'.md5(implode('.',$uri).$this->AppRequest->app);
    
    if($this->Caching != false){
      if($Route = $this->Caching->getData($route_cache_key)){
        return $Mapping;
      }
    }
    
    
    foreach($this->Routes as $Route){
      if($CheckedRoute = $this->checkRouteMatch(clone $Route, $Request)){
        if($this->Caching != false){
          $this->Caching->setData($route_cache_key,$Route);
        }
        return $CheckedRoute;
      }
        
    }
    return false;

  }
  
  private function checkMatchFromUrl(\Framework\Router\Route $Route, \Framework\HTTP\RequestInterface $Request)
  {
    return false;
    $index_start = 0;
    $index = $index_start;
    $total_extra =  count($url) <= 2 ? 0 : count($url)  - 2;
    
    
    if($url[$index] != $Mapping->getController() && $Mapping->getController() != '*' )
      return false;
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
    
    return str_replace($replace,$replace_by,$Mapping->getPattern());
  }
  
  /// checken of er nog parts in het patroon zitten (aantal moet kloppen)
  private function checkRouteMatch($Mapping, $uri){
    $parts = $Mapping->getPatternArray();
    
    if(count($parts) != count($uri))
      return false;

    foreach($parts as $key => $slug){
      // is dit een regex element? -> kijk dan na bij extra of het bestaat
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        // haal de reguliere expressie op voor dit specifiek item
        $reserved_words = self::getReservedWords();
        $slug_key = substr($slug, 1, -1);
        $regex = $Mapping->getSlugMatch($slug_key);

        // dit kan een reserved word zijn, kijk dan of de reguliere expressie hiermee klopt (of eender wat kan/mag zijn)
        if(in_array($slug_key,$reserved_words)){
          
          if($slug_key == 'controller' && ($Mapping->getController() == '*' || preg_match($Mapping->getController(),$uri[$key])) ){
            $Mapping->setController($uri[$key]);
            continue;
          }
          
          if($slug_key == 'action' && ($Mapping->getAction() == '*' || preg_match($Mapping->getAction(),$uri[$key])) ){
            $Mapping->setAction($uri[$key]);
            continue;
          }
              
        }
        
        // als het geen wildcard is en het match niet OF het is een reserved word -> geen match
        if( in_array($slug_key,$reserved_words) || !( $regex == '*' || preg_match($regex,$uri[$key])))
          return false;
          
      }else{
        // easy literal match
        if(trim($slug) != trim($uri[$key]))
          return false;
      }
      
    }
    return $Mapping;
    
  }
    
  public function getRoutes($app = null)
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