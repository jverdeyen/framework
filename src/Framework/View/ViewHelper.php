<?php
namespace Framework;

class ViewHelper{

  private $file;
  protected $request;
  protected $template;

  public function __construct(){
    $this->Request = Request::getInstance();
    $this->controller = $this->Request->getController();
    $this->action = $this->Request->getAction();
    $this->language = $this->Request->getLanguage();
    $this->app = $this->Request->getApp();

    $this->template = new Template($this->app['template_dir']);
    $this->setDefaultFile();
    $this->preDispatch();
    $this->init();
    
  }

  
  public function fetch(){
    $this->postDispatch();
    return $this->template->fetch($this->file);
  }
  
  public function setFile($file){
    $this->file = $file;
  }
  
  private function setDefaultFile(){
    $file = str_ireplace('ViewHelper','',get_class($this));
    $file = explode('\\',$file);
    $file = end($file);
    $file = substr_replace($file, strtolower(substr($file, 0, 1)), 0, 1);
    $file = strtolower(preg_replace('/[A-Z]/', '_$0',$file));
    $this->file = 'helper/'.$file.'.tpl';
  }
  
  public function getUrl($params,$app = false,$cleanup = true){
    $Uri = new Uri($this->Request);
    return $Uri->getUrl($params,$app,$cleanup);
  }
  
  public function preDispatch(){}
  public function postDispatch(){}
  public function init(){}
}