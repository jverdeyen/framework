<?php
namespace Framework;

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
      
    }catch(\Exception $e){
      
      Logger::getInstance()->exceptionHandler($e);

      //TODO check of er een error controller bestaat, anders standaard error tonen
      $errorController = new ErrorController();
      $errorController->exception = $e;
      $errorController->init();

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