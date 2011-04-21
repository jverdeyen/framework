<?php
namespace Framework;

class Bootstrap{
  
  public function start(){
    
    try{
      @include_once dirname(__FILE__).'/../../config/config.php';
      @include_once dirname(__FILE__).'/../../include/global_vars.php';
      @include_once dirname(__FILE__).'/../../config/db.php';
      include_once dirname(__FILE__).'/Autoloader.php';
      
      Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__));
      Bootstrap::checkBootstrap();
      Autoloader::getInstance()->registerNamespace(APP_NAME,ROOT_DIR.'./');
      
      Logger::getInstance()->setErrorHandlers();
      echo FrontController::getInstance()->route();
      
    }catch(\Exception $e){
      //TODO check of er een error controller bestaat, anders standaard error tonen
      $errorController = new ErrorController();
      $errorController->exception = $e;
      $errorController->init();

    }
    
  }
  
  public function checkBootstrap(){
    //TODO alle nodige vars checken 
    $mandatory[] = 'APP_NAME';
    $mandatory[] = 'ROOT_DIR';
    
    foreach($mandatory as $value){
      if(!defined($value)){
        throw new \Exception('Var name '.$value.' is not defined as const var.');
      }
    }
  }
}
?>