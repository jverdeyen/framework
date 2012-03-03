<?php
namespace Framework\Tests\Framework\Test\Router;

use Framework;

class LinkerTest extends \PHPUnit_Framework_TestCase 
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
    
    $Server = new \Framework\HTTP\Data\Server();
    $Server->set('SERVER_NAME','www.test.be');
    $Server->set('QUERY_STRING','/producten/naam/123/');
    
    $Request = new \Framework\HTTP\Request(null,null,null,null, $Server);
    $AppRequest = new \Framework\HTTP\AppRequest($Request,$Config);
    $AppRequest->initApp();
    
    return array(array($Config,$AppRequest));
  }
  
  /**
   * @dataProvider setupConfig
  */
  public function testcheckMatchRouteLink(\Framework\Config\Config $Config, \Framework\HTTP\AppRequest $AppRequest)
  {  
    $Linker = new \Framework\Router\Linker($AppRequest, $Config, null); // disable caching
    
    /*
    general_overview:
      pattern: '/{controller}/show/{id}'
      controller: '*'
      action: list
      app: frontend
      extra:
          id:
            match: '/^[0-9]+$/'
        
    */
    $Route = new \Framework\Router\Route('first_page',$Config->get('mapping.frontend.general_overview'), $Config->get('mapping.reserved_words'));
    $Link = new \Framework\Router\Link(array('controller' => 'eender', 'action' => 'list', 'extra' => array('id' => 1)));
    $this->assertTrue($Linker->checkMatchRouteLink($Link,$Route));
    
    
    // /producten/{name}/{id}
    $Route = new \Framework\Router\Route('first_page',$Config->get('mapping.frontend.first_page'), $Config->get('mapping.reserved_words'));
    
    $Link = new \Framework\Router\Link(array('controller' => 'product', 'action' => 'category', 'extra' => array('name' => 'test', 'id' => 1)));
    $this->assertTrue($Linker->checkMatchRouteLink($Link,$Route));
    
    $Link = new \Framework\Router\Link(array('controller' => 'product', 'action' => 'category', 'extra' => array('name' => 'tes--t', 'id' => 1123)));
    $this->assertTrue($Linker->checkMatchRouteLink($Link,$Route));
    
    $Link = new \Framework\Router\Link(array('controller' => 'product', 'action' => 'category', 'extra' => array('title' => 'test', 'id' => 1)));
    $this->assertFalse($Linker->checkMatchRouteLink($Link,$Route));
    
    
  }
  
  /**
   * @dataProvider setupConfig
  */
  public function testgetUrl(\Framework\Config\Config $Config, \Framework\HTTP\AppRequest $AppRequest)
  {    
    
    $Linker = new \Framework\Router\Linker($AppRequest,$Config,null); // disable caching
    // /producten/{name}/{id}
    //$Route = new \Framework\Router\Route('first_page',$Config->get('mapping.frontend.first_page'), $Config->get('mapping.reserved_words'));
    $Linker->getUrl(array('controller' => 'product', 'action' => 'category', 'extra' => array('id' => 2, 'name' => 'test')));
     
    $Linker->getUrl(array('controller' => 'random', 'action' => 'list', 'extra' => array('id' => 2)));
  }
}