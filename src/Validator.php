<?php
namespace Framework;

class Validator{
  
  private function __construct(){}
  private function __clone(){}
  
  const EMAIL_INVALID = 'invalidEmailError';
  const NAME_INVALID = 'invalidNameError';
  const LOCATION_INVALID = 'invalidLocationError';
  const PHONE_INVALID = 'invalidPhoneError';
  const MESSAGE_INVALID = 'invalidMessageError';
  const REQUIRED = 'requiredError';
  const TOO_SHORT = 'tooShortError';

  
  public function validate($var,$type){
    if(is_callable('self::validate'.ucfirst($type))){
       return call_user_func('self::validate'.ucfirst($type),$var); 
   }else
      throw new \BadFunctionCallException('Method validate'.ucfirst($type).' is undefined in '.get_class($this));
  }
  
  public function validateEmail($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    if(!filter_var($var, FILTER_VALIDATE_EMAIL))
      return self::EMAIL_INVALID;
    
    return true;
  }
  
  public function validateName($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    if(strlen($var) <= 1)
      return self::TOO_SHORT;
    
    //TODO: check speciale tekens
  
    return true;
  }
  
  public function validatePhone($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    $var = preg_replace('[\D]', '', $var);
    
    if(strlen($var) <= 7)
      return self::PHONE_INVALID;
  
    return true;
  }
  
  public function validateDob($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    if(strlen($var) <= 5)
      return self::TOO_SHORT;
  
    return true;
  }
  
  public function validateCity($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    if(strlen($var) <= 1)
      return self::TOO_SHORT;
    
    //TODO: check speciale tekens
  
    return true;
  }
  
  public function validateMessage($var){
    $validateString = self::validateString($var);
    
    if(!($validateString === true))
      return $validateString;
    
    if(strlen($var) <= 5)
      return self::TOO_SHORT;
    
    return true;
  }
  
  public function validateString($var){
    if (strlen(trim($var)) > 0)
      return true;
    return self::REQUIRED;
  }
  
}


?>