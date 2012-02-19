<?php
namespace Framework\Tests\Framework\Test\Router;

use Framework;

class RouterTest extends \PHPUnit_Framework_TestCase 
{
  
  public function testRouterSetup() {
    $config = __DIR__.'/../Config/yml/config.yml';
    $env = __DIR__.'/../Config/yml/config_env.yml';
    $mapping = __DIR__.'/../Config/yml/mapping.yml';
    
    $YamlConfigReader = new \Framework\Config\YamlConfigReader();
    $YamlConfigReader->add($config);
    $YamlConfigReader->add($env);
    $YamlConfigReader->add($mapping);
    
    $Config = new \Framework\Config\Config($YamlConfigReader);
    $Request = new \Framework\HTTP\Request(new \Framework\HTTP\Server(), $Config);
    $Router = new \Framework\Router\Router($Request,$Config);
        
  }
}