<?php
namespace Framework;

try{

  include_once dirname(__FILE__).'/../../config/config.php';
  include_once dirname(__FILE__).'/../../include/global_vars.php';
  include_once dirname(__FILE__).'/../../config/db.php';
  include_once dirname(__FILE__).'/Autoloader.php';
  
  Autoloader::getInstance()->registerNamespace('Framework',dirname(__FILE__));
  Autoloader::getInstance()->registerNamespace(APP_NAME,ROOT_DIR.'./');
  Autoloader::getInstance()->register();
  
  Logger::getInstance()->setErrorHandlers();
  echo FrontController::getInstance()->route();

}catch(\Exception $e){
  //TODO check of er een error controller bestaat, anders standaard error tonen
  $errorController = new ErrorController();
  $errorController->exception = $e;
  $errorController->init();

}
?>