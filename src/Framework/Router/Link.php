<?php
namespace Framework\Router;

class Link{
  
  public $controller = 'index';
  public $action = 'index';
  public $app = '';
  public $extra = array();

  
  public function __construct()
  {

  }
  
  
  public function getController(){ return $this->controller; }
  public function getAction(){ return $this->action; }
  public function getApp(){ return $this->app; }
  public function getExtra(){ return $this->extra; }
  public function setController($x){ $this->controller = $x;}
  public function setAction($x){ $this->action = $x;}
  public function setApp($x){ $this->app = $x;}
  public function setExtra($x){ $this->extra = $x;}
}
?>