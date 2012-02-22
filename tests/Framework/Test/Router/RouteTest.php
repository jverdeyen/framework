<?php
namespace Framework\Tests\Framework\Test\Router;

use Framework;

class RoutTest extends \PHPUnit_Framework_TestCase 
{

  public function testRouteCreation()
  {
        
    $config = array();
    $config['mapping']['reserved_words'][] = 'controller';
    $config['mapping']['reserved_words'][] = 'language';
    $config['mapping']['reserved_words'][] = 'action';
    $config['mapping']['reserved_words'][] = 'app';
    $config['mapping']['front']['page1']['pattern'] = '/startpagina/{artikel}/{naam}/{niet_gekend}/{controller}/';
    $config['mapping']['front']['page1']['controller'] = 'start';
    $config['mapping']['front']['page1']['action'] = 'artikel';
    $config['mapping']['front']['page1']['app'] = 'front';
    $config['mapping']['front']['page1']['extra']['naam']['match'] = '*';
    $config['mapping']['front']['page1']['extra']['naam']['default'] = '';
    $config['mapping']['front']['page1']['extra']['artikel']['match'] = '/^[0-9]+$/';
    
    
    $ArrayConfigReader = new \Framework\Config\ArrayConfigReader($config);
    $ArrayConfigReader->add(array('env' => 'dev'));
    $Config = new \Framework\Config\Config($ArrayConfigReader);
      
    $Route = new \Framework\Router\Route('first_page',$Config->get('mapping.front.page1'), $Config->get('mapping.reserved_words'));
    $this->assertEquals($Route->getPatternArray(),array('startpagina','{artikel}','{naam}','{niet_gekend}','{controller}'));
    $this->assertArrayHasKey('niet_gekend', $Route->getExtra(), 'Vergeten extra field is ingevuld vanuit het patroon.');
    $this->assertEquals('*',$Route->extra['niet_gekend']['match']);
    $this->assertEquals('*',$Route->extra['naam']['match']);
    $this->assertFalse(isset($Route->extra['controller']['match']));
    $this->assertEquals(1,$Route->getSlugPatternIndex('artikel'));
    $this->assertEquals(4,$Route->getSlugPatternIndex('controller'));
    $this->assertEquals('*',$Route->getSlugMatch('niet_gekend'));
    $this->assertEquals('start',$Route->getSlugMatch('controller'));
    $this->assertEquals('artikel',$Route->getSlugMatch('action'));
      
  }
}