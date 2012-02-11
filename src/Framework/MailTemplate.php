<?php
namespace Framework;

class MailTemplate {

  private $vars = array();
  private $template;
  
  public function __construct($template = false){
    if(!($template === false))
      $this->template = $template;
  }
  
  public function set($name,$value){
    $this->vars[$name] = $value;
  }
  
  public function fetch($template = false){
    if($template === false)
      $template = $this->template;
    
    $object_vars = get_object_vars($this);
    if(is_array($object_vars)){
      foreach($object_vars as $key => $var){
        if(!in_array($key,array('vars','template'))){
          $this->set($key,$var);
        }
      }
    }

    $Twig = TwigLoader::getTwigLoader('string');
    $template = $Twig->loadTemplate($this->template);
    return $template->render($this->vars);
  
  }
	
	public function setText($template){
	  $this->template = $template;
	}
}
?>