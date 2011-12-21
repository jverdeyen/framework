<?php
namespace Framework;

class Controller{

  protected $action;
  protected $controller;
  protected $language;
  protected $app;
  protected $extra_params;
  protected $request;
  protected $session;
  protected $response;
  protected $i18nTxt;
  
  private $options;
  
  public function __construct($options = array()){
    $this->options = $options;
    $this->request = Request::getInstance();
    $this->response = Response::getInstance();
    $this->session = Session::getInstance();
    $this->action = $this->request->getAction();
    $this->controller = $this->request->getController();
    $this->doControllerMapping();
    $this->extra_params = Uri::getExtraParams();
    $this->language = $this->request->getLanguage();
    $this->app = $this->request->getApp();
  }
  
  public function redirect($params,$http_response_code = false){
    Uri::redirect($params,$http_response_code);
  }
  
  public function getUrl($params,$app = false, $cleanup = true){
    return Uri::getUrl($params, $app, $cleanup);
  }
  
  public function getFlash(){
    return new View\Flash();
  }
      
  private function doControllerMapping(){
    if(is_array($this->options['controller_mapping'])){
	    if(array_key_exists(strtolower($this->controller),$this->options['controller_mapping'])){
	      $this->controller = $this->options['controller_mapping'][strtolower($this->controller)];
	    } 
	  }
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
  
  public static function setNotice($message){
    View\Flash::setNotice($message);
  }
  
  public static function setError($message){
    View\Flash::setError($message);
  }
  
  public static function setSuccess($message){
    View\Flash::setSuccess($message);
  }
  
  public static function setWarning($message){
    View\Flash::setWarning($message);
  }
  

}
?>