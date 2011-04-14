<?php
namespace Framework;

class ViewHelper{

  private $file;
  protected $request;
  protected $template;

  public function __construct(){
    $this->request = Request::getInstance();
    $this->controller = $this->request->getController();
    $this->action = $this->request->getAction();
    $this->language = $this->request->getLanguage();
    
    if(!(strpos(strtolower(get_class($this)),BACKEND_APP_NAME) === false))
      $this->template = new Template(BACKEND_TEMPLATE_DIR);
    else
      $this->template = new Template(FRONTEND_TEMPLATE_DIR);
    $this->setDefaultFile();
    $this->init();
  }
  
  public function init(){
    
  }
  
  public function fetch(){
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
}