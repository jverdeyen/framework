<?php
namespace Framework;

class Controller{

  protected $action;
  protected $controller;
  protected $language;
  protected $app;
  protected $extra_params;
  protected $request;
  protected $response;
  protected $i18nTxt;
  
  public function __construct(){
    $this->request = Request::getInstance();
    $this->response = Response::getInstance();
    $this->action = $this->request->getAction();
    $this->controller = $this->request->getController();
    $this->extra_params = Uri::getExtraParams();
    $this->language = $this->request->getLanguage();
    $this->app = $this->request->getApp();
  }
  
  public function init(){  
    if(is_callable(array($this, $this->action.'Action')))
      return $this->{$this->action.'Action'}();
    elseif(is_callable(array($this, 'indexAction')))
      return $this->{'indexAction'}();
    else
      throw new BadFunctionCallException('Method '.$this->action.'Action is undefined in '.get_class($this));  
  }
  
  public function indexAction(){
    return '';
  }
  
  public function preDispatch(){}
  
  public function postDispatch(){}
  
  protected function loadI18nText(){
    $i18n = i18nTxt::getInstance($this->language);
    $this->i18nTxt = $i18n->getTxt();
  }
  

}
?>