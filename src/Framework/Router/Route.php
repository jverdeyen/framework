<?php
namespace Framework\Router;

class Route{
  
  public $pattern = '';
  public $controller = 'index';
  public $action = 'index';
  public $language = '';
  public $app = '';
  public $extra = array();
  public $key = '';
  
  public $reserverd_words = array();
  public $pattern_array = array();

  
  public function __construct($key,$data,$reserved_words = array())
  {
    $this->key = $key;
    $this->reserved_words = $reserved_words;
    $this->pattern = $data['pattern'];
    
    if($data['controller'] != null){
      $this->controller = $data['controller'];
    }
      
    
    if($data['action'] != null){
      $this->action = $data['action'];
    }
      
    if($data['language'] != null){
      $this->language = $data['language'];
    }
    
    if($data['app'] != null){
      $this->app = $data['app'];
    }
    
    if($data['extra'] != null){
      $this->extra = $data['extra'];
    }
    
    $this->pattern_array = $this->getPatternArray();    
    $this->insertUnknownExtraValues();
    $this->insertUnknownMatchingPatterns($this->reserved_words);
  }
  
  /**
   * Wanneer in het patroon velden staan, die niet in de extra array staan, plaats deze dan erbij in
   */
  public function insertUnknownExtraValues()
  {
    if(!is_array($this->pattern_array)){
      return $this->extra;
    }
    
    foreach($this->pattern_array as $slug)
    {
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        
        $slug_key = substr($slug, 1, -1);
        
        // als de slug key niet bestaat in de extra array -> steek die er dan bij in
        if(!array_key_exists($slug_key,$this->extra)){
          $this->extra[$slug_key] = array();
        }
          
      }
      
    }
    return $this->extra;
  }
  
  /**
   * Wanneer er voor een patroon geen matching pattern is toegevoegd, laat dan alles toe (=*)
   */
  public function insertUnknownMatchingPatterns($reserved_words = array())
  {
    foreach($this->extra as $key => $value)
    {
      if(in_array($key,$reserved_words)){
        //$this->extra[$key] = $this->$key;
        continue;
      }
        
        
      if($this->extra[$key]['match'] == ''){
        $this->extra[$key]['match'] = '*';
      } 
    }
    
    return $this->extra;
  }
  
  public function getPatternArray()
  {
    $array =  explode('/', $this->pattern);
    
		if(end($array) == ''){
		  array_pop($array);
		}
		
		if(reset($array) == ''){
		  array_shift($array);
		}
		
		return $array;
  }
  
  /**
   * Geef de match terug van een patroon (bij een reserved woord, geef deze terug uit de route zelf)
   */
  public function getSlugMatch($key)
  {
    if(trim($this->extra[$key]['match']) != ''){
      return $this->extra[$key]['match'];
    }
    
    if(in_array($key,$this->reserved_words)){
      return $this->{$key};
    }
    
    return false;
  }
  
  /**
   * Geef de index locatie van een variabele in het patroon terug
   */
  public function getSlugPatternIndex($name)
  {
    foreach($this->pattern_array as $key => $slug)
    {
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        $slug_key = substr($slug, 1, -1);
        
        if($name == $slug_key){
          return $key;
        }
          
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
  public function getLanguage(){ return $this->language; }
  public function getExtra(){ return $this->extra; }
  public function getKey(){ return $this->key; }
  public function setController($x){ $this->controller = $x;}
  public function setAction($x){ $this->action = $x;}
}
?>