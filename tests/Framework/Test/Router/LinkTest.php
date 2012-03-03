<?php
namespace Framework\Tests\Framework\Test\Router;

use Framework;

class LinkTest extends \PHPUnit_Framework_TestCase 
{

  public function testLinkGetUrl()
  {
    $Link = new \Framework\Router\Link();
    
    $url_simple = array('server' => 'www.test.be', 'controller' => 'products', 'action' => 'list', 'language' => '');
    $this->assertEquals($Link->getUrl($url_simple),'http://www.test.be/products/list');
    
    $url_simple_language = array('server' => 'www.test.be', 'controller' => 'products', 'action' => 'list', 'language' => 'de');
    $this->assertEquals($Link->getUrl($url_simple_language),'http://www.test.be/de/products/list');
    
    $url_simple_default = array('server' => 'www.test.be', 'controller' => '', 'action' => 'list', 'language' => 'de');
    $this->assertEquals($Link->getUrl($url_simple_default),'http://www.test.be/de/index/list');
    
    $url_simple_extra = array('server' => 'www.test.be', 'controller' => '', 'action' => 'list', 'extra' => array('1','abc'));
    $this->assertEquals($Link->getUrl($url_simple_extra),'http://www.test.be/index/list/1/abc');
    
    $url_simple_extra_cleanup = array('server' => 'www.test.be', 'controller' => 'test spatie', 'action' => 'list list', 'extra' => array('1','abc de fg'));
    $this->assertEquals($Link->getUrl($url_simple_extra_cleanup),'http://www.test.be/test-spatie/list-list/1/abc-de-fg');
    
    $url_simple_extra_cleanup_extreme = array('server' => 'www.test.be', 'controller' => 'test spatië', 'action' => 'lïst lîst', 'extra' => array('(1)','abc de fg'));
    $this->assertEquals($Link->getUrl($url_simple_extra_cleanup_extreme),'http://www.test.be/test-spatie/list-list/1/abc-de-fg');
    
    $url_simple_default = array('server' => 'www.short.be', 'controller' => '', 'action' => '', 'language' => 'es');
    $this->assertEquals($Link->getUrl($url_simple_default),'http://www.short.be/es');
    
    $url_simple_default = array('server' => 'www.short.be', 'controller' => '', 'action' => 'test', 'language' => 'es');
    $this->assertEquals($Link->getUrl($url_simple_default),'http://www.short.be/es/index/test');
    
    $url_simple_default = array('server' => 'www.short.be', 'controller' => 'contr', 'action' => '', 'language' => 'es');
    $this->assertEquals($Link->getUrl($url_simple_default),'http://www.short.be/es/contr');
    
    $url_simple_default = array('server' => 'www.short.be', 'controller' => '', 'action' => '', 'language' => 'es', 'extra' => array('muhaha'));
    $this->assertEquals($Link->getUrl($url_simple_default),'http://www.short.be/es/index/index/muhaha');
  }
}