<?php
namespace Framework\Tests\Framework;
use Framework;
use Framework\Request;

class RequestTest extends \PHPUnit_Framework_TestCase {
      
  public function testRequestObject(){
    $_SERVER['SERVER_NAME'] = 'www.framework.be';
    //$Request = Request::getInstance();

  }
    
}

?>