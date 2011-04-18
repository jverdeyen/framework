<?php
namespace Framework;

class Mailer {
  
  private $mailer;
  private $message;

  public function __construct() {
    require_once(dirname(__FILE__).'/vendor/SwiftMailer/swift_required.php');
    
    $transport = \Swift_MailTransport::newInstance();
    $this->mailer = \Swift_Mailer::newInstance($transport);
    $this->message = \Swift_Message::newInstance();
  }
  
  public function __call($function, $params) {
    //call function on message object
    call_user_func_array(array($this->message, $function), $params);
  }
  
  public function attach($path) {
    $attachment = \Swift_Attachment::fromPath($path);  
    $this->message->attach($attachment);
  }
  
  public function send() {
    $encoding = \Swift_Encoding::get8BitEncoding();
    $this->message->setEncoder($encoding);
    return $this->mailer->send($this->message);
  }

}