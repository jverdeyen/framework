<?php
namespace Framework;

class Service{
  
  public $controller;
  public $action;
  public $language;
  public $request;
  protected $extra_params;
  
  public function __construct(){
    $this->Request = new Request();
    $this->controller = $this->getRequest()->getController();
    $this->action = $this->getRequest()->getAction();
    $this->language = $this->getRequest()->getLanguage();
    $this->extra_params = $this->getRequest()->getExtraParams();
  }
  
  public function getController() { return $this->getRequest()->getController(); } 
  public function getAction() { return $this->getRequest()->getAction(); } 
  public function getLanguage() { return $this->getRequest()->getLanguage(); } 
  public function getRequest() { return Request::getInstance(); } 
  public function getExtraParams() { return $this->getRequest()->getExtraParams(); } 
  
  public function redirect($params,$http_response_code = false){
    $Uri = new Uri($this->Request);
    $Uri->redirect($params,$http_response_code);
  }
  
  public function getUrl($params,$app = false,$cleanup = true){
    $Uri = new Uri($this->Request);
    return $Uri->getUrl($params,$app,$cleanup);
  }

}

?>