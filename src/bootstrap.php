<?php
namespace Framework;

try{

  include_once dirname(__FILE__).'/../../config/config.php';
  include_once dirname(__FILE__).'/../../include/global_vars.php';
  include_once dirname(__FILE__).'/../../config/db.php';
  include_once dirname(__FILE__).'/Autoloader.php';
  
  Framework\Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__));
  Framework\Autoloader::getInstance()->registerNamespace(APP_NAME,ROOT_DIR.'./');
  Framework\Autoloader::getInstance()->register();
  
  Framework\Logger::getInstance()->setErrorHandlers();
  echo Framework\FrontController::getInstance()->route();

}catch(\Exception $e){
  //TODO check of er een error controller bestaat, anders standaard error tonen
  $errorController = new ErrorController();
  $errorController->exception = $e;
  $errorController->init();

}
?>