<?php
namespace Framework\Router;

class Mapping{
  
  private $pattern = '';
  private $controller = 'index';
  private $action = 'index';
  private $app = '';
  private $extra = array();
  private $key = '';

  
  public function __construct($key,$data){
    $this->key = $key;
    $this->pattern = $data['pattern'];
    
    if($data['controller'] != null)
      $this->controller = $data['controller'];
    
    if($data['action'] != null)
      $this->action = $data['action'];
    
    if($data['app'] != null){
      $this->app = $data['app'];
    }
    
    if($data['extra'] != null)
      $this->extra = $data['extra'];       
  }
  
  public function fillUpSlugsInExtra(){
    $pattern_array = $this->getPatternArray();
    foreach($pattern_array as $slug){
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        
        $slug_key = substr($slug, 1, -1);
        
        // als de slug key niet bestaat in de extra array -> steek die er dan bij in
        if(!array_key_exists($slug_key,$this->extra))
          $this->extra[$slug_key] = array();
      }
      
    }
  }
  
  public function fillUpMatches($reserved_words = array()){
    foreach($this->extra as $key => $value){
      if(in_array($key,$reserved_words))
        continue;
        
      if($this->extra[$key]['match'] == ''){
        $this->extra[$key]['match'] = '*';
      } 
    }
  }
  
  public function getPatternArray(){
    $array =  explode('/', $this->pattern);
		if(end($array) == ''){
		  array_pop($array);
		}
		if(reset($array) == ''){
		  array_shift($array);
		}
		return $array;
  }
  
  public function getExtraRegex($key,$reserved_words){
    if(trim($this->extra[$key]['match']) != ''){
      return $this->extra[$key]['match'];
    }
    
    if(in_array($key,$reserved_words)){
      if($key == "controller")
        return $this->getController();
    }
    
    return false;
  }
  
  // geef de locatie van 'name' in het patroon
  public function getSlugPatternIndex($name){
    $pattern_array = $this->getPatternArray();
    foreach($pattern_array as $key => $slug){
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        $slug_key = substr($slug, 1, -1);
        if($name == $slug_key)
          return $key;
      }
    }
    return false;
  }
  
  
  public function getExtraArray(Request $Request){
    $array = array();
    $uri_parts = $Request->getParams();

    foreach($this->extra as $key => $value){
      // find the matching value in the url, it has to be a slug
      $url_index = $this->getSlugPatternIndex($key);
      $value = $uri_parts[$url_index];
      $array[] = $value;
    }
    
    return $array;
  }
  
  public function getPattern(){ return $this->pattern; }
  public function getController(){ return $this->controller; }
  public function getAction(){ return $this->action; }
  public function getApp(){ return $this->app; }
  public function getExtra(){ return $this->extra; }
  public function getKey(){ return $this->key; }
  public function setController($x){ $this->controller = $x;}
  public function setAction($x){ $this->action = $x;}
}
?>