<?php
namespace Framework;
use Framework\Exception\ControllerNotFoundException;

class Bootstrap{
  
  public static function start(){
    
    try{
      @include_once dirname(__FILE__).'/../../config/config.php';
      @include_once dirname(__FILE__).'/../../include/global_vars.php';
      @include_once dirname(__FILE__).'/../../config/db.php';
      include_once dirname(__FILE__).'/Autoloader.php';
      
      Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__).'/');
      Bootstrap::checkBootstrap();
      Autoloader::getInstance()->registerNamespace(APP_NAME,ROOT_DIR.'./');
      
      Logger::getInstance()->setErrorHandlers();
      echo FrontController::getInstance()->route();
      
    }catch(ControllerNotFoundException $e){
      // Last resort catching
      Uri::redirect(array('controller' => 'index'),301);
      
    }catch(\Twig_Error_Loader $e){
      // Last resort catching
      Uri::redirect(array('controller' => 'index'),301);
    
    }catch(\BadFunctionCallException $e){
      Uri::redirect(array('controller' => 'index'),301);
      
    }catch(\Exception $e){
      $ErrorController = new ErrorController();
      $ErrorController->init($e);
    }
    
  }
  
  public function checkBootstrap(){

    $mandatory[] = 'APP_NAME';
    $mandatory[] = 'ROOT_DIR';
    $mandatory[] = 'DB_CHARSET';
    $mandatory[] = 'ENVIRONMENT';
    $mandatory[] = 'DEFAULT_CONTROLLER';
    $mandatory[] = 'DEFAULT_ACTION';
    $mandatory[] = 'APPS';

    foreach($mandatory as $value){
      if(!defined($value)){
        throw new \Exception('You should define'.$value.', to make the framwork work..');
      }
    }
    
    return true;
  }
}
?>