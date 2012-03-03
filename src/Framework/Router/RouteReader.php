<?php
namespace Framework\Router;

class RouteReader
{
  
  public $Config = null;
  public $Cache = null;
  
  private $Routes = array();
      
  public function __construct(  \Framework\Cache\CacheInterface $Cache = null,
                                \Framework\Config\Config $Config = null)
  {
    
    $this->Config  = $Config;
    $this->Cache = $Cache;
  }
  
  public function setCache(\Framework\Cache\CacheInterface $Cache)
  {
    $this->Cache = $Cache;
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