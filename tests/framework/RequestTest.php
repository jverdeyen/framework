<?php
namespace Lib\Tests\Framework;
use Lib;
use Lib\Request;

class RequestTest extends \PHPUnit_Framework_TestCase {
      
  public function testRequestObject(){
    $Request = Request::getInstance();
  }
    
}

?>