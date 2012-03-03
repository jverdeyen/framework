<?php
namespace Framework\Router;

class Link{
  
  public $controller = 'index';
  public $action = 'index';
  public $language = '';
  public $app = '';
  public $extra = array();
  public $server = '';
  
  
  public function __construct($array)
  {
    $this->initData($array);
  }
  
  public function getUrl($data = false, $https = false)
  {
    if($data != false && is_array($data)){
      $this->initData($data);
    }
    
    $url = '';
    $protocol = 'http';
    
    if($https === true){
     $protocol = 'https';
    }
    
    if($this->server != ''){
      $url = $protocol.'://'.$this->server;
    }
    
    $parts = $this->getUrlParts($data); 
    $parts = $this->getQueryString($parts);    
    return $url.'/'.$parts;    
  }
  
  public function getUrlParts($data = false){
    if($data != false && is_array($data)){
      $this->initData($data);
    }
    
    $parts = array();
    
    if($this->language != ''){
      $parts[] = $this->language;
    }
    
    if($this->controller != ''){
      $parts[] = $this->controller;
    }
    elseif($this->action != '' || count($this->extra) > 0){
      $parts[] = 'index';
    }
    
    if($this->action != ''){
      $parts[] = $this->action;
    }
    elseif(count($this->extra) > 0){
      $parts[] = 'index';
    }
    
    if(is_array($this->extra)){
      $parts = array_merge($parts,$this->extra);
    }
    
    return $parts;
  }
  
  public function getQueryString($data = array())
  {
    $data = implode('/',$data);    
    $data = strtolower($data);
    $data = str_replace(' ','-',$data); 
	  $data = \Framework\Functions\General::replaceAccents($data);
	  $data = preg_replace('/[^A-Za-z0-9\-._\/]/', '', $data);
	  return $data;
  }
  
  public function initData($data)
  {
    $this->controller = $data['controller'];
    $this->action = $data['action'];
    $this->language = $data['language'];
    $this->app = $data['app'];
    $this->extra = $data['extra'];
    $this->server = $data['server'];
  }
  
  public function getExtraByKey($key)
  {
    return $this->extra[$key];
  }
  
  public function getController(){ return $this->controller; }
  public function getAction(){ return $this->action; }
  public function getApp(){ return $this->app; }
  public function getExtra(){ return $this->extra; }
  public function setController($x){ $this->controller = $x;}
  public function setAction($x){ $this->action = $x;}
  public function setApp($x){ $this->app = $x;}
  public function setExtra($x){ $this->extra = $x;}
  public function setLanguage($x){ $this->language = $x; }
  public function setServer($x){ $this->server = $x; }
}
?>