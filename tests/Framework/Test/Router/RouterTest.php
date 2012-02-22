<?php
namespace Framework\Tests\Framework\Test\Router;

use Framework;

class RouterTest extends \PHPUnit_Framework_TestCase 
{
  
  public function setupConfig()
  {
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
  public function testRouterSetup(\Framework\Config\Config $Config)
  {
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/producten/naam/123/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initApp();
    
    $Router = new \Framework\Router\Router($AppRequest,null); // disable caching
    $Router->route();
    //var_dump($Router);
        
  }
}