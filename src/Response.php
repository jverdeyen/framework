<?php 
namespace Framework;

class Response {
 
  private static $instance;

  private $title;
  private $keywords;
  private $description;
  private $canonical;
  private $index;
  private $follow;
  
  private function __construct() {
    $this->title = $this->keywords = $this->description = $this->canonical = '';
    $this->index = $this->follow = true;
  }
  
  public static function getInstance() {
    if(!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  
  public function setTitle($title) {
    $this->title = $title;
  }

  public function getTitle() {
    return $this->title;
  }
  
  public function setKeywords($keywords) {
    $this->keywords = $keywords;
  }

  public function getKeywords() {
    return $this->keywords;
  }
  
  public function setCanonical($description) {
    $this->canonical = $canonical;
  }

  public function getCanonical() {
    return $this->canonical;
  }
  
  public function setDescription($description) {
    $this->description = $description;
  }

  public function getDescription() {
    return $this->description;
  }
  
  public function setIndex($index) {
    $this->index = $index;
  }

  public function getIndex() {
    return $this->index;
  }
  
  public function setFollow($follow) {
    $this->follow = $follow;
  }

  public function getFollow() {
    return $this->follow;
  }
 
 
 
}