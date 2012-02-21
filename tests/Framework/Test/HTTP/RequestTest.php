<?php
namespace Framework\Tests\Framework\Test\HTTP;

use Framework;

class RequestTest extends \PHPUnit_Framework_TestCase 
{
  
  public function setupConfig(){
    $config = __DIR__.'/../Config/yml/config.yml';
    $env = __DIR__.'/../Config/yml/config_env.yml';
    $mapping = __DIR__.'/../Config/yml/mapping.yml';

    $YamlConfigReader = new \Framework\Config\YamlConfigReader();
    $YamlConfigReader->add($config);
    $YamlConfigReader->add($env);
    $YamlConfigReader->add($mapping);
    $Config = new \Framework\Config\Config($YamlConfigReader);
    
    return array(array($Config));
  }
  
  /**
   * @dataProvider setupConfig
  */
  public function testAppRequestMultiLang(\Framework\Config\Config $Config) 
  {
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','admin.test.be');
    $Server->set('QUERY_STRING','/nl/contact/form/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initAll();
    
    $this->assertEquals('admin',$AppRequest->findAppKey());
    $this->assertEquals('contact',$AppRequest->findController());
    $this->assertEquals('form',$AppRequest->findAction());
    $this->assertEquals('nl',$AppRequest->findLanguage());
  }
  
  /**
   * @dataProvider setupConfig
  */
  public function testAppRequestSingleLang(\Framework\Config\Config $Config)
  {
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/contact/form/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initAll();
    
    $this->assertEquals('www',$AppRequest->findAppKey());
    $this->assertEquals('contact',$AppRequest->findController());
    $this->assertEquals('form',$AppRequest->findAction());
    $this->assertEquals('en',$AppRequest->findLanguage());
    
  }
  
  /**
   * @dataProvider setupConfig
  */
  public function testAppGetDefault(\Framework\Config\Config $Config)
  {
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','dummy.test.be');
    $Server->set('QUERY_STRING','/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initAll();
    
    $this->assertEquals('admin',$AppRequest->findAppKey());
    $this->assertEquals('index',$AppRequest->findController());
    $this->assertEquals('index',$AppRequest->findAction());
    $this->assertEquals('nl',$AppRequest->findLanguage());
    
  }
}

?>