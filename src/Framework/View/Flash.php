<?php
namespace Framework\View;
use Framework\Session;

class Flash{

  public static function setNotice($message){
    self::set('notice',$message);
  }
  
  public static function setError($message){
    self::set('error',$message);
  }
  
  public static function setSuccess($message){
    self::set('success',$message);
  }
  
  public static function setWarning($message){
    self::set('warning',$message);
  }
  
  private static function set($type,$message){
    
    $messages = Session::getInstance()->get('flash_message_'.$type);

    if(is_array($message)){
      if(is_array($messages)){
        array_merge($message,$messages);
      }else{
        $messages = $message;
      }
    }else{
      if(is_array($messages)){
        $messages[] = $message;
      }else{
        $messages = array($message);
      }
    }
    
    Session::getInstance()->set('flash_message_'.$type,$messages); 
  }

  
  public static function get($type = 'notice'){
    $messages = Session::getInstance()->get('flash_message_'.$type);
    Session::unsetVar('flash_message_'.$type);
    return $messages;
  }
}