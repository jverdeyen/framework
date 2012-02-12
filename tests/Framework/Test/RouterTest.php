<?php
namespace Framework\Tests\Framework;

use Framework;

class RouterTest extends \PHPUnit_Framework_TestCase {
      
  public function testGetUrl(){
    define('MULTI_LANGUAGE', false);
    define('DEFAULT_CONTROLLER', 'index');
    define('DEFAULT_ACTION', 'index');
    define('DEFAULT_LANGUAGE', 'nl');
    define('APPS',serialize(array('default' => array('name' => 'frontend', 'url' => 'http://www.framework.be/'))));
        
    $Uri = \Framework\Uri::getInstance();
    $url = $Uri->getUrl(array('controller' => 'asset', 'action' => 'js'));
 
  }

    
}

?>