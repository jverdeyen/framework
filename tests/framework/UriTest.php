<?php
namespace Framework\Tests\Framework;

use Framework,
    Framework\Request,
    Framework\Uri;

class UriTest extends \PHPUnit_Framework_TestCase {
      
  public function testUriParamsMultiLangNoExtra(){
    define(MULTI_LANGUAGE, true);
    $_SERVER['QUERY_STRING'] = 'de/controller/action/';
    
    $Uri = Uri::getInstance();
    $this->assertEquals($Uri->getParam(0),'de');
    $this->assertEquals($Uri->getParam(1),'controller');
    $this->assertEquals($Uri->getParam(2),'action');
    $this->assertFalse($Uri->getExtraParams());
  }
  
  public function testUriParamsMultiLang(){
    define(MULTI_LANGUAGE, true);
    $_SERVER['QUERY_STRING'] = 'de/controller/action/extra/';
    
    $Uri = Uri::getInstance();
    $this->assertEquals($Uri->getParam(0),'de');
    $this->assertEquals($Uri->getParam(1),'controller');
    $this->assertEquals($Uri->getParam(2),'action');
    $this->assertEquals($Uri->getExtraParams(),array('extra'));
  }
  
  public function testUriParamsSingleLangNoExtra(){
    define('MULTI_LANGUAGE', false);
    $_SERVER['QUERY_STRING'] = 'controller/action/';
    
    $Uri = Uri::getInstance();
    $this->assertEquals($Uri->getParam(0),'controller');
    $this->assertEquals($Uri->getParam(1),'action');
    $this->assertFalse($Uri->getExtraParams());
  }
  
  public function testUriParamsSingleLang(){
    define('MULTI_LANGUAGE', false);
    $_SERVER['QUERY_STRING'] = 'controller/action/extra/';
    
    $Uri = Uri::getInstance();
    $this->assertEquals($Uri->getParam(0),'controller');
    $this->assertEquals($Uri->getParam(1),'action');
    $this->assertEquals($Uri->getExtraParams(),array('extra'));
  }
  
  public function testUriGetUrlSingleLanguage(){
    define('MULTI_LANGUAGE', false);
    define(DEFAULT_CONTROLLER, 'index');
    define(DEFAULT_ACTION, 'index');
    define(DEFAULT_LANGUAGE, 'nl');
    define(APPS,serialize(array('www' => array('name' => 'frontend', 'url' => 'http://www.framework.be/'))));
    
    $_SERVER['SERVER_NAME'] = 'www.framework.be';
    
    $this->assertEquals(Uri::getUrl(array('controller' => 'test')),'http://www.framework.be/test/');  
    $this->assertEquals(Uri::getUrl(array('controller' => 'index')),'http://www.framework.be/');                 
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'index')),'http://www.framework.be/controller/');     
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action')),'http://www.framework.be/controller/action/');
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action', 'extra' => array('1','2'))),'http://www.framework.be/controller/action/1/2/');
    
  }
  
  public function testUriGetUrlMultiLanguage(){
    define('MULTI_LANGUAGE', true);
    define(LANGUAGES,serialize(array( 1 => 'nl', 2 => 'fr', 3 => 'de', 4 => 'en')));
    define(DEFAULT_CONTROLLER, 'index');
    define(DEFAULT_ACTION, 'index');
    define(DEFAULT_LANGUAGE, 'nl');
    define(APPS,serialize(array('www' => array('name' => 'frontend', 'url' => 'http://www.framework.be/') )));
                    
    $_SERVER['QUERY_STRING'] = 'de/controller/action/extra/';
    $_SERVER['SERVER_NAME'] = 'www.framework.be';

    $this->assertEquals(Uri::getUrl(array('controller' => 'test')),'http://www.framework.be/de/test/');  
    $this->assertEquals(Uri::getUrl(array('controller' => 'index')),'http://www.framework.be/de/');                 
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'index')),'http://www.framework.be/de/controller/');     
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action')),'http://www.framework.be/de/controller/action/');
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action', 'extra' => array('1','2'))),'http://www.framework.be/de/controller/action/1/2/');
        
  }
  
  public function testUriGetUrlMultiLanguageDefaultLanguage(){
    define('MULTI_LANGUAGE', true);
    define(LANGUAGES,serialize(array( 1 => 'nl', 2 => 'fr', 3 => 'de', 4 => 'en')));
    define(DEFAULT_CONTROLLER, 'index');
    define(DEFAULT_ACTION, 'index');
    define(DEFAULT_LANGUAGE, 'nl');
    define(APPS,serialize(array('www' => array('name' => 'frontend', 'url' => 'http://www.framework.be/') )));
                    
    $_SERVER['SERVER_NAME'] = 'www.framework.be';

    $this->assertEquals(Uri::getUrl(array('controller' => 'test')),'http://www.framework.be/nl/test/');  
    $this->assertEquals(Uri::getUrl(array('controller' => 'index')),'http://www.framework.be/nl/');                 
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'index')),'http://www.framework.be/nl/controller/');     
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action')),'http://www.framework.be/nl/controller/action/');
    $this->assertEquals(Uri::getUrl(array('controller' => 'controller', 'action' => 'action', 'extra' => array('1','2'))),'http://www.framework.be/nl/controller/action/1/2/');
        
  }
    
}

?>