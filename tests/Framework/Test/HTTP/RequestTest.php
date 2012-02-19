<?php
namespace Framework\Tests\Framework\Test\HTTP;

use Framework;

class RequestTest extends \PHPUnit_Framework_TestCase 
{
  
  
  public static function setupRequest() {
    $config = __DIR__.'/../Config/yml/config.yml';
    $env = __DIR__.'/../Config/yml/config_env.yml';
    $mapping = __DIR__.'/../Config/yml/mapping.yml';

    $YamlConfigReader = new \Framework\Config\YamlConfigReader();
    $YamlConfigReader->add($config);
    $YamlConfigReader->add($env);
    $YamlConfigReader->add($mapping);
    $Config = new \Framework\Config\Config($YamlConfigReader);
    
    $Server = new \Framework\HTTP\Server();
    $Server->set('SERVER_NAME','admin.test.be');
    
    $Request = new \Framework\HTTP\Request($Server, $Config);
    return array(array($Request));
  }
  
  /**
   * @dataProvider setupRequest
  */
  public function testRequestSetup(\Framework\HTTP\Request $Request) {
    //var_dump($Request->determineApp());
  }
}

?>