<?php
namespace Framework;

class Controller{

  protected $action;
  protected $controller;
  protected $language;
  protected $app;
  protected $extra_params;
  protected $Request;
  protected $session;
  protected $response;
  protected $i18nTxt;
  
  private $options;
  
  public function __construct(Request $Request){
    $this->Request = $Request;
    $this->response = Response::getInstance();
    $this->session = Session::getInstance();
    $this->action = $this->Request->getAction();
    $this->controller = $this->Request->getController();
    $this->extra_params = $this->Request->getExtraParams();
    $this->language = $this->Request->getLanguage();
    $this->app = $this->Request->getApp();
  }
  
  public function redirect($params,$http_response_code = false){
    if($params['controller'] == '')
      $params['controller'] = $this->controller;
      
    $Uri = new Uri($this->Request);
      
    $Uri->redirect($params,$http_response_code);
  }
  
  public function getUrl($params,$app = false, $cleanup = true){
    $Uri = new Uri($this->Request);
    
    if($params['controller'] == '')
      $params['controller'] = $this->controller;
    
    return $Uri->getUrl($params, $app, $cleanup);
  }
  
  public function getFlash(){
    return new View\Flash();
  }
      
  
  public function init()
  {  
    if(is_callable(array($this, $this->action.'Action')))
    {
      return $this->{$this->action.'Action'}();
    }      
    elseif(is_callable(array($this, 'indexAction'))){
      return $this->{'indexAction'}();
    }
      
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