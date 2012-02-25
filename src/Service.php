<?php
namespace Framework;

class Service{
  
  public $controller;
  public $action;
  public $language;
  public $request;
  public $Request; // for the love of god, I used the uppercase version 2
  protected $extra_params;
  
  public function __construct(){
    $this->request = Request::getInstance();
    $this->Request = $this->request;
    $this->controller = $this->getRequest()->getController();
    $this->action = $this->getRequest()->getAction();
    $this->language = $this->getRequest()->getLanguage();
    $this->extra_params = $this->getRequest()->getExtraParams();
  }
  
  public function getController() { return $this->getRequest()->getController(); } 
  public function getAction() { return $this->getRequest()->getAction(); } 
  public function getLanguage() { return $this->getRequest()->getLanguage(); } 
  public function getRequest() { return Request::getInstance(); } 
  public function getExtraParams() { return Uri::getExtraParams(); } 
  
  public function redirect($params,$http_response_code = false){
    Uri::redirect($params,$http_response_code);
  }
  
  public function getUrl($params,$app = false,$cleanup = true){
    return Uri::getUrl($params,$app,$cleanup);
  }

}

?>