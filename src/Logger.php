<?php
namespace Framework;

class Logger {
  
  private static $_instance = null;
  
  private function __construct() {}
  
  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function setErrorHandlers() {
    error_reporting(0);
    set_error_handler(array($this, 'errorHandler'), E_ALL & ~E_NOTICE);
    set_exception_handler(array($this, 'exceptionHandler'));
    register_shutdown_function(array($this, 'shutdownHandler'));
  }
  
  public function errorHandler($code, $message, $filename, $line_number, $vars=array()) {
    
    //errors that we need to log
    $error_codes = array (
                     E_ERROR        => 'PHP Fatal error',
                     E_WARNING      => 'PHP Warning',
                     E_USER_ERROR   => 'PHP User Error',
                     E_USER_WARNING => 'PHP User Warning'
                   );
    
    if(array_key_exists($code, $error_codes)) {
      $message = $message . ' in ' . $filename . ' on line ' . $line_number;
      error_log($error_codes[$code] . ':  ' . $message, 0);
      
      $backtrace = $this->get_debug_print_backtrace(2);
      $this->log($error_codes[$code], $message, $backtrace);
    }
  }
  
  public function exceptionHandler($exception) {
          
    $traceline = "#%s %s(%s): %s(%s)";
    $message = "Uncaught exception '%s' with message '%s' thrown in %s on line %s";
    $trace = $exception->getTrace();
    $result = array();
    foreach($trace as $key => $point) {
      $result[] = sprintf(
        $traceline,
        $key,
        $point['file'],
        $point['line'],
        $point['function'],
        implode(', ', $point['args'])
      );
    }
    
    //trace always ends with {main}
    $result[] = '#' . ++$key . ' {main}';
    $backtrace = implode('<br />', $result);
    
    $message = sprintf(
      $message,
      get_class($exception),
      $exception->getMessage(),
      $exception->getFile(),
      $exception->getLine()
    );
  
    //log the error in the default php error logfile
    error_log('PHP Fatal error:  ' . $message, 0);
        
    return $this->log('PHP Fatal error', $message, $backtrace);
  }

  public function shutdownHandler() {
    $error = error_get_last();
    //only handle fatal errors
    var_dump($error);
    $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
  }
  
  public function log($type, $message, $backtrace='') {
    $env = ENVIRONMENT;
    $message .= "\n\nEnvironment:\n".$env."\n\n";
    $message .= "Type:\n".$type."\n\n";
    $message .= "Message:\n".$message."\n\n";
    $message .= "User-agent:\n".$_SERVER['HTTP_USER_AGENT']."\n\n";
    $message .= "Ip-address:\n".$_SERVER['REMOTE_ADDR']."\n\n";
    $message .= "Backtrace:\n".$backtrace."\n\n";
    $message .= "Get-parameters:\n".print_r($_GET, true)."\n";
    $message .= "Post-parameters:\n".print_r($_POST, true)."\n";
    $message .= "Cookie-parameters:\n".print_r($_COOKIE, true)."\n";
    $message .= "Session-parameters:\n".print_r($_SESSION, true)."\n";
    $message .= "Server-parameters:\n".print_r($_SERVER, true);
    
    if($env == 'dev')
      echo nl2br($message);
      
    return nl2br($message);
  }

  private function get_debug_print_backtrace($traces_to_ignore=1) {
    $traces = debug_backtrace();
    $ret = array();
    foreach($traces as $i => $call) {
      if($i < $traces_to_ignore) {
        continue;
      }
  
      $object = '';
      if(isset($call['class'])) {
        $object = $call['class'].$call['type'];
        if(is_array($call['args'])) {
          foreach($call['args'] as &$arg) {
            $this->get_arg($arg);
          }
        }
      }
  
      $ret[] = '#'.str_pad($i - $traces_to_ignore, 3, ' ')
               .$object.$call['function'].'('.implode(', ', $call['args'])
               .') called at ['.$call['file'].':'.$call['line'].']';
    }
    return implode("\n", $ret);
  }
  
  private function get_arg(&$arg) {
    if(is_object($arg)) {
      $arr = (array)$arg;
      $args = array();
      foreach($arr as $key => $value) {
        if(strpos($key, chr(0)) !== false) {
          $key = ''; //private variable found
        }
        $args[] = '['.$key.'] => '.$this->get_arg($value);
      }
      $arg = get_class($arg) . ' Object ('.implode(',', $args).')';
    }
  }
  
}
