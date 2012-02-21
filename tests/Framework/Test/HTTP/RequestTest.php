<?php
namespace Framework\Tests\Framework\Test\HTTP;

use Framework;

class RequestTest extends \PHPUnit_Framework_TestCase 
{
  
  
  public static function setupRequestMultiLang() {
    $config = __DIR__.'/../Config/yml/config.yml';
    $env = __DIR__.'/../Config/yml/config_env.yml';
    $mapping = __DIR__.'/../Config/yml/mapping.yml';

    $YamlConfigReader = new \Framework\Config\YamlConfigReader();
    $YamlConfigReader->add($config);
    $YamlConfigReader->add($env);
    $YamlConfigReader->add($mapping);
    $Config = new \Framework\Config\Config($YamlConfigReader);
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','admin.test.be');
    $Server->set('QUERY_STRING','/nl/contact/form/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    return array(array($AppRequest));
  }
  
  public static function setupRequestSingleLang() {
    $config = __DIR__.'/../Config/yml/config.yml';
    $env = __DIR__.'/../Config/yml/config_env.yml';
    $mapping = __DIR__.'/../Config/yml/mapping.yml';

    $YamlConfigReader = new \Framework\Config\YamlConfigReader();
    $YamlConfigReader->add($config);
    $YamlConfigReader->add($env);
    $YamlConfigReader->add($mapping);
    $Config = new \Framework\Config\Config($YamlConfigReader);
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/contact/form/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    return array(array($AppRequest));
  }
  
  /**
   * @dataProvider setupRequestMultiLang
  */
  public function testAppRequestMultLang(\Framework\HTTP\AppRequest $AppRequest) {

    $this->assertEquals('admin',$AppRequest->determineAppKey());
    $AppRequest->initApp();
    $this->assertEquals('contact',$AppRequest->determineController());
    $this->assertEquals('form',$AppRequest->determineAction());
  }
  
  /**
   * @dataProvider setupRequestSingleLang
  */
  public function testAppRequestSingleLang(\Framework\HTTP\AppRequest $AppRequest) {

    $this->assertEquals('www',$AppRequest->determineAppKey());
    $AppRequest->initApp();
    $this->assertEquals('contact',$AppRequest->determineController());
    $this->assertEquals('form',$AppRequest->determineAction());
  }
}

?>