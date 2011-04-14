<?php
namespace Framework;

class Service{
  
  public $controller;
  public $action;
  public $language;
  public $request;
  protected $extra_params;
  
  public function __construct(){
    $this->request = Request::getInstance();
    $this->controller = $this->request->getController();
    $this->action = $this->request->getAction();
    $this->language = $this->request->getLanguage();
    $this->extra_params = Uri::getExtraParams();
  }

}

?>