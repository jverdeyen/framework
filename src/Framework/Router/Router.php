<?php
namespace Framework\Router;

class Router{
  
  private $Request = null;
  private $Mappings = array();
  private $MatchedMapping = false;
  private $Caching = false;
  private static $reserved_words = array('controller','language','action','app');
  private static $instance = null;
  
  private $mapping_file;
  
  public function __construct(Request $Request,$mapping_file = false){
    if($mapping_file === false)
      $mapping_file = ROOT_DIR.'include/mapping.yml';
      
    $this->mapping_file = $mapping_file;
    $this->Request = $Request;
  }
  
  public function setCaching($Caching){
    $this->Caching = $Caching;
  }
  
  /**
   * Converts the request object into a matching mapping
   */
  public function route(){
    $this->readMappings();
  
    if(!$this->isEnabled())
      return false;
    
    $Mapping = $this->findAMapping();

    if($Mapping != false){
      $this->MatchedMapping = $Mapping;
      $this->Request->setController($Mapping->getController());
      $this->Request->setAction($Mapping->getAction());
      $this->Request->setApp($Mapping->getApp());
      $this->Request->setExtraParams($this->Request);
    }
  }
  
  public function findAUrlMapping($uri){
    $this->readMappings();
    foreach($this->Mappings as $Mapping){
      $result = $this->checkMatchFromUrl($Mapping,$uri);
      if($result != false){
        return $result;
      }else{
        continue;
      }
    }
    
    return false;

  }
  
  private function findAMapping($uri = false){
    if($uri === false){
      $Uri = new  \Framework\Uri($this->Request);
      $uri = $Uri->getParams();
    }
      
    $uri_key = 'router_mapping_'.md5(implode('.',$uri).$this->Request->getAppName());
    
    if($this->Caching != false){
      if($Mapping = $this->Caching->getData($uri_key)){
        return $Mapping;
      }
    }
    
    
    foreach($this->Mappings as $Mapping){
      if($MappingChecked = $this->checkMatch(clone $Mapping,$uri)){
        if($this->Caching != false){
          $this->Caching->setData($uri_key,$Mapping);
        }
        return $MappingChecked;
      }
        
    }
    return false;

  }
  
  private function checkMatchFromUrl($Mapping, $url){
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
      $regex = $Mapping->getExtraRegex($key,$this->reserved_words);
     
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
  private function checkMatch($Mapping, $uri){
    $parts = $Mapping->getPatternArray();
    
    if(count($parts) != count($uri))
      return false;

    foreach($parts as $key => $slug){
      // is dit een regex element? -> kijk dan na bij extra of het bestaat
      if(preg_match('/^{[a-zA-Z0-9_-]+}$/', $slug)){
        // haal de reguliere expressie op voor dit specifiek item
        $reserved_words = self::getReservedWords();
        $slug_key = substr($slug, 1, -1);
        $regex = $Mapping->getExtraRegex($slug_key,$this->reserved_words);

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
    
  private function readMappings()
  {
    if($this->Caching != false){
      if(!($this->Mappings = $this->Caching->getData('mapping_yml_'.$this->Request->getAppName()))){
        $this->readMappingsFromFile();
        $this->Caching->setData('mapping_yml_'.$this->Request->getAppName(),$this->Mappings);
      }            
    } else {
      $this->readMappingsFromFile();
    }    
  }
  
  private function readMappingsFromFile()
  {
    if(!file_exists($this->mapping_file))
      return false;
      
    $mapping = \Symfony\Component\Yaml\Yaml::parse($this->mapping_file);
    $mapping = $mapping[$this->Request->getAppName()];

    if(!is_array($mapping))
      return false;

    foreach($mapping as $key => $value){  
      $Mapping = new Mapping($key,$value);
      $Mapping->fillUpSlugsInExtra();
      $Mapping->fillUpMatches(self::getReservedWords());
      $this->Mappings[] = $Mapping;
    }
    
    return $this->Mapping;
  }
  
  public static function getReservedWords()
  {
    return self::$reserved_words;
  }

  public function foundMapping(){
    return !($this->MatchedMapping == false);
  }
  
  public function isEnabled(){
    if(count($this->Mappings) > 0)
      return true;
    return false;
  }
  
}
?>