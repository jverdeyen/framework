<?php
namespace Framework;

class i18nTxt{

	private static $lang;
	private static $text;
	private static $instance = null;

	public static function getInstance($lang){
 		if(is_null(self::$instance))
 			self::$instance = new i18nTxt($lang);
		return self::$instance;
	}


	private function __construct($lang){
	  if(file_exists(ROOT_DIR.'i18n/'.$lang.'/index.php'))
	    include_once ROOT_DIR.'i18n/'.$lang.'/index.php';
	  self::$text = $_i18nTxt;
	}
	
	public function getTxt($key = false){
	  if($key === false)
	    return self::$text;
	  return self::$text[$key];
	}
		
	public function getLang(){
	  return $lang;
	}
	
	public function getEmailText($filename,$lang = false){
	  if($language === false)
	    $lang = self::$lang;

	  $file = ROOT_DIR.'i18n/'.$lang.'/mail/'.$filename.'.tpl';
	  
	  if(file_exists($file))
	    return file_get_contents($file);
	  else
	    throw new \Exception('i18n error, file not found:'.$file);
	}
 
	private function __clone(){}

}
