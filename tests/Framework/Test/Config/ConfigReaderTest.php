<?php
namespace Framework\Tests\Framework\Test\Config;

use Framework;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class ConfigReaderTest extends \PHPUnit_Framework_TestCase 
{
 

  public function testDIConfig(){
    
    $file = __DIR__.'/yml/config.yml';
    $env_file = __DIR__.'/yml/config_env.yml';
    
    $YamlConfigReader = new \Framework\Config\YamlConfigReader($file);
    $YamlConfigReader->add($env_file);
        
    $serviceContainer = new ContainerBuilder();
    $serviceContainer
        ->register('config', '\Framework\Config\Config')
        ->addArgument($YamlConfigReader);
    
    
    
    $Config = $serviceContainer->get('config');
    $this->assertEquals($Config->get('name'),'AppNamespace');
   
   //$loader->load('config.yml');
    //$loader = new YamlFileLoader($serviceContainer, new FileLocator(__DIR__.'/../../../../src/Framework/DependencyInjection/config'));
    // $config->get('env');
      //$container = new ContainerBuilder();
      //$configDefinition = new Definition('\Framework\Config\Config');
      //$container->setDefinition('config', $configDefinition);
      //$container->get('config')->get('env');
      //$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($container);
      //$loader->load(__DIR__.'../services.yml');
  }

  public function testYamlConfigReader()
  {
    $file = __DIR__.'/yml/config.yml';
    $env_file = __DIR__.'/yml/config_env.yml';
    
    $YamlConfigReader = new \Framework\Config\YamlConfigReader($file);
    $YamlConfigReader->add($env_file);
    
    $Config = new \Framework\Config\Config($YamlConfigReader);

    $this->assertEquals($Config->get('name'),'AppNamespace');
    $this->assertEquals($Config->get('app.name'),'App');
    $this->assertEquals($Config->get('apps.admin.name'),'backend');
    $this->assertTrue($Config->get('apps.admin.clean_url'));
    $this->assertEquals($Config->get('apps.admin.name_copy'),'AppNamespace');
    $this->assertEquals($Config->get('dir.root'),'/var/www/');
    $this->assertEquals($Config->get('apps.admin.dir'),'/var/www/admin/');
    $this->assertEquals($Config->get('apps.admin.namespace'),'AppNamespace/Admin');
    $this->assertEquals($Config->get('environment'),'dev');
    $this->assertEquals($Config->get('env'),'dev');
    $this->assertEquals($Config->get('apps.admin.environment'),'dev');
    $this->assertEquals($Config->get('apps.admin.languages'), array('nl','fr','de'));
    $this->assertEquals($Config->get('app'),array('name' => 'App', 'website' => 'www.test.be'));
   
  }
  
  public function testArrayConfigReader()
  {
    $config = array();
    $config['name'] = 'App';
    $config['app']['name'] = 'App';
    $config['apps']['admin']['name'] = 'backend';
    $config['apps']['admin']['clean_url'] = true;
    $config['apps']['admin']['namespace'] = 'AppNamespace';
    $config['namespace'] = '%name%/%apps.admin.namespace%'; 
    
    $ArrayConfigReader = new \Framework\Config\ArrayConfigReader($config);
    $ArrayConfigReader->add(array('env' => 'dev'));
    
    $Config = new \Framework\Config\Config($ArrayConfigReader);
    
    $this->assertEquals($Config->get('name'),'App');
    $this->assertEquals($Config->get('app.name'),'App');
    $this->assertEquals($Config->get('apps.admin.name'),'backend');
    $this->assertTrue($Config->get('apps.admin.clean_url'));
    $this->assertEquals($Config->get('apps.admin.namespace'),'AppNamespace');
    $this->assertEquals($Config->get('namespace'),'App/AppNamespace');
    $this->assertEquals($Config->get('env'),'dev');
  }
    
}

?>