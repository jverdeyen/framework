<?php
namespace Lib;

class TwigLoader {

  private static $template;
  private static $string;
  
  public function getTwigLoader($type = 'template', $template_dir = false){
    if($type == 'template')
      return self::getTemplate($template_dir);
    elseif($type == 'string')
      return self::getString();
  }
  
  private function getString(){
    if(!self::$string) {
      $loader = new \Twig_Loader_String();
      $twig = new \Twig_Environment($loader, array(
        'cache' => TEMPLATE_CACHE_DIR,
        'auto_reload' => true,
        'trim_blocks' => true
      ));
      $twig->addExtension(new \Twig_Extensions_Extension_Text());
      self::$string = $twig;
    }
    return self::$string;
  }
  
  private function getTemplate($template_dir){

    if(!self::$template) {

      $loader = new \Twig_Loader_Filesystem($template_dir);
      $twig = new \Twig_Environment($loader, array(
        'cache' => TEMPLATE_CACHE_DIR,
        'auto_reload' => true,
        'trim_blocks' => true,
        //'strict_variables' => true
      ));
       $twig->addExtension(new \Twig_Extensions_Extension_Text());
      self::$template = $twig;
    }
    
    return self::$template;
  }
}
?>