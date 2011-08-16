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
    $this->controller = $this->getRequest()->getController();
    $this->action = $this->getRequest()->getAction();
    $this->language = $this->getRequest()->getLanguage();
    $this->extra_params = Uri::getExtraParams();
  }
  
  public function getController() { return $this->getRequest()->getController(); } 
  public function getAction() { return $this->getRequest()->getAction(); } 
  public function getLanguage() { return $this->getRequest()->getLanguage(); } 
  public function getRequest() { return Request::getInstance(); } 
  public function getExtraParams() { return Uri::getExtraParams(); } 
  
  public function redirect($params,$http_response_code = false){
    Uri::redirect($params,$http_response_code);
  }
  
  public function getUrl($params){
    return Uri::getUrl($params);
  }

}

?>