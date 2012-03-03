<?php
namespace Framework\Router;

class Linker extends RouteReader
{
  
  public $Config = null;
  public $Cache = null;
  
  private $Routes = array();
      
  public function __construct(  \Framework\HTTP\RequestInterface $AppRequest,
                                \Framework\Config\Config $Config = null,
                                \Framework\Cache\CacheInterface $Cache = null
                                )
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
  
  public function getUrl($data = array(), $https = false)
  {
    $Link = new \Framework\Router\Link($data);
    $Route = $this->getMatchingRoutesLink($Link) ;
    
    if($Route !== false){
      $query_string = $this->getRouteQueryString($Link,$Route);
      $server = $this->Config->get('apps.'.$Route->getApp().'.url');
      if($server == ''){
        $server = $this->Config->get('apps.'.$this->AppRequest->app.'.url');
      }
      
      $query_string = 'http://'.$server.$query_string;
    }
    else{
      $server = $this->Config->get('apps.'.$this->AppRequest->app.'.url');
      $Link->setServer($server);
      $query_string = $Link->getUrl(false,$https);
    }
  
    return $query_string;
  }
  
  public function getMatchingRoutesLink(\Framework\Router\Link $Link, $Routes = null)
  {
    // TODO check op cache 
    
    if($Routes == null){
      $Routes = $this->getRoutes();
    }
    foreach($Routes as $Route){
      
      if($this->checkMatchRouteLink($Link, $Route) !== false){
         return $Route;
      }
    }
    
    return false;

  }

  public function checkMatchRouteLink(\Framework\Router\Link $Link, \Framework\Router\Route $Route)
  {
    if($Link->getController() != $Route->getController() && $Route->getController() != '*' ){
      return false;
    }

    if($Route->getAction() != '*' && 
            ( 
              ($Route->getAction() != $Link->getAction() && $Link->getAction != '') || 
              ($Route->getAction() != 'index' && $Link->getAction() == '') )){
      return false;       
    }

    $route_extras = $Route->getExtra();
    $extras_without_reserved_words = $route_extras;
    $reserved_words = $this->Config->get('mapping.reserved_words');;
    
    foreach($reserved_words as $reserved){
      unset($extras_without_reserved_words[$reserved]);
    }

    if(count($extras_without_reserved_words) != count($Link->getExtra())){
      return false;
    }

    foreach($route_extras as $key => $value)
    {  
      if(in_array($key,$reserved_words)){
        continue;
      }
      
      if($Link->getExtraByKey($key) == null){
        return false;
      }
      
      $regex = $Route->getSlugMatch($key);

      if($regex == '*' || preg_match($regex,$Link->getExtraByKey($key))) {     
        continue;
      }
      
      return false;         
    }
    
    return true;
    
   
   
  }
  
  public function getRouteQueryString(\Framework\Router\Link $Link, \Framework\Router\Route $Route)
  {
    $url_pattern = $Link->getUrlParts();
    $route_pattern = $Route->getPatternArray();
    $start_index = -1;

    foreach($route_pattern as $key => $value)
    {  

      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $value)){
        $pattern_key = substr($value, 1, -1);
        
        if($pattern_key == 'controller'){
          $route_pattern[$key] = $url_pattern[$start_index+1];
        }
        elseif($pattern_key == 'action'){
          $route_pattern[$key] = $url_pattern[$start_index+2];
        }
        elseif($pattern_key == 'language'){
          $route_pattern[$key] = $url_pattern[$start_index];
        }
        else{
         $route_pattern[$key] = $url_pattern[$pattern_key];
        }
      }
    }

    return '/'.$Link->getQueryString($route_pattern);
  }
  
  
}
?>