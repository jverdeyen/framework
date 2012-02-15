<?php
namespace Framework;
use Framework\Exception\ControllerNotFoundException;

class Bootstrap{
  
  public function __construct(Request $Request){
    $this->Request = $Request;
  }
  public function start(){

    try{      
      require 'vendor/.composer/autoload.php';    
      
      require_once dirname(__FILE__).'/vendor/Twig/Extensions/Autoloader.php';
      \Twig_Extensions_Autoloader::register();
      
      $this->checkBootstrap();
      Logger::getInstance()->setErrorHandlers();
      
      $FrontController = new FrontController($this->Request);
      echo $FrontController->route();

    }
    /*catch(ControllerNotFoundException $e){
      // Last resort catching
      if(self::runningInDev()){
        echo Logger::getInstance()->exceptionHandler($e);
        exit;
      }
      Uri::redirect(array('controller' => 'index'),301);
      
    }catch(\Twig_Error_Loader $e){
      if(self::runningInDev()){
        echo Logger::getInstance()->exceptionHandler($e);
        exit;
      }
      // Last resort catching
      Uri::redirect(array('controller' => 'index'),301);
    
    }catch(\BadFunctionCallException $e){
      if(self::runningInDev()){
        echo Logger::getInstance()->exceptionHandler($e);
        exit;
      }
      Uri::redirect(array('controller' => 'index'),301);
      
    }*/
    catch(\Exception $e){
      if(self::runningInDev()){
        echo Logger::getInstance()->exceptionHandler($e);
        exit;
      }
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
  
  public static function runningInDev(){
    return (ENVIRONMENT == 'dev');
  }
}
?>