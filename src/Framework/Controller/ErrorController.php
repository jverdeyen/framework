<?php
namespace Framework;

class ErrorController{

  public function init(\Exception $e){
        
    $Request = Request::getInstance();
    $app = $Request->getApp();
    
    // Look for the default error controller of the application // otherwise go for the index controller
    $controller_name = "\\".APP_NAME."\\App\\".ucfirst($app['name'])."\\Controller\\Error";
    
	  if(class_exists($controller_name)){
	    $controller = new $controller_name();
	    return $controller->init($e);
	  }else{
	    Uri::redirect(array('controller' => 'index'),301);
	  }

  }

}