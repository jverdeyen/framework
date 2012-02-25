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
    $this->assertEquals($AppRequest->getController(),'product');
    $this->assertEquals($AppRequest->getAction(),'category');
    $this->assertEquals($AppRequest->name,'naam');
    $this->assertEquals($AppRequest->id,'123');
        
  }
  
  
  /**
    * @dataProvider setupConfig
   */
  public function testerRouteMatchingSimple(\Framework\Config\Config $Config)
  {
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/producten/product-naam/65465165165/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $Route = new \Framework\Router\Route('first_page',$Config->get('mapping.frontend.first_page'), $Config->get('mapping.reserved_words'));
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initApp();
    $Router = new \Framework\Router\Router($AppRequest,null); // disable caching
    
    $MatchingRoute = $Router->compareRouteRequest($Route,$Request);
    $this->assertEquals($MatchingRoute->getController(),'product');
    $this->assertEquals($MatchingRoute->getAction(),'category');
    $this->assertEquals($AppRequest->name,'product-naam');
    $this->assertEquals($AppRequest->id,'65465165165');
  }
  
  /**
    * @dataProvider setupConfig
   */
  public function testerRouteMatchingLessSimple(\Framework\Config\Config $Config)
  {
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/products/show/1/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $Route = new \Framework\Router\Route('first_page',$Config->get('mapping.frontend.general_overview'), $Config->get('mapping.reserved_words'));
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initApp();
    $Router = new \Framework\Router\Router($AppRequest,null); // disable caching
    
    $MatchingRoute = $Router->compareRouteRequest($Route,$Request);
    $this->assertEquals($MatchingRoute->getController(),'products');
    $this->assertEquals($MatchingRoute->getAction(),'list');
    $this->assertEquals($AppRequest->id,'1');
  }
}