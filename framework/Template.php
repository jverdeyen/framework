<?php
namespace Lib;

class Template {

  private $vars = array();
  private $globals = array();
  private $filename;
  private $template_dir;
  
  public function __construct($template_dir = false){
    if(!($template_dir === false))
      $this->template_dir = $template_dir;
  }
  
  public function set($name,$value){
    $this->vars[$name] = $value;
  }
  
  public function addGlobal($name,$value){
    $this->globals[$name] = $value;
  }
  
  public function fetch($template_file = false){
    $object_vars = get_object_vars($this);
  
    if(is_array($object_vars)){
      foreach($object_vars as $key => $var){
        if(!in_array($key,array('vars','filename','template_dir'))){
          $this->set($key,$var);
        }
      }
    }

    if($template_file === false)
      $template_file = $this->filename;

    $Twig = TwigLoader::getTwigLoader('template',$this->template_dir);
    
    if(is_array($this->globals)){
      foreach($this->globals as $key => $global)
        $Twig->addGlobal($key,$global);
    }
    
    $template = $Twig->loadTemplate($template_file);
    return $template->render($this->vars);
  }
	
	public function setFilename($filename){
	  $this->filename = $filename;
	}
}
?>