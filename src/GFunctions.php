<?php
namespace Framework;

class GFunctions{
    
  public static function checkZeroOneValue($value){
    return (in_array($value,array('0','1')) && is_numeric($value));
  }
  
  public static function checkValidId($value){
      return is_numeric($value);
  }
  
  public static function checkValidNumber($value){
    return is_numeric($value);
  }
  
  public static function checkValidFile($value){
    return is_string($value) && (trim($value) != '');
  }
  
  public static function checkValidString($value){
    return is_string($value);
  }
  
  public static function replaceAccents($newphrase){

  	$newphrase = str_replace("ÃƒÅ“","U",$newphrase);
  	$newphrase = str_replace("Ã…Â?","S",$newphrase);
  	$newphrase = str_replace("Ã„Â?","G",$newphrase);
  	$newphrase = str_replace("Ãƒâ€¡","C",$newphrase);
  	$newphrase = str_replace("Ã„Â°","I",$newphrase);
  	$newphrase = str_replace("Ãƒâ€“","O",$newphrase);
  	$newphrase = str_replace("ÃƒÂ¼","u",$newphrase);
  	$newphrase = str_replace("Ã…Å¸","s",$newphrase);
  	$newphrase = str_replace("ÃƒÂ§","c",$newphrase);
  	$newphrase = str_replace("Ã„Â±","i",$newphrase);
  	$newphrase = str_replace("ÃƒÂ¶","o",$newphrase);
  	$newphrase = str_replace("Ã„Å¸","g",$newphrase);

  	$newphrase = str_replace("Ãœ","U",$newphrase);
  	$newphrase = str_replace("Å?","S",$newphrase);
  	$newphrase = str_replace("Ä?","G",$newphrase);
  	$newphrase = str_replace("Ã‡","C",$newphrase);
  	$newphrase = str_replace("Ä°","I",$newphrase);
  	$newphrase = str_replace("Ã–","O",$newphrase);
  	$newphrase = str_replace("Ã¼","u",$newphrase);
  	$newphrase = str_replace("ÅŸ","s",$newphrase);
  	$newphrase = str_replace("Ã§","c",$newphrase);
  	$newphrase = str_replace("Ä±","i",$newphrase);
  	$newphrase = str_replace("Ã¶","o",$newphrase);
  	$newphrase = str_replace("ÄŸ","g",$newphrase);


  	$newphrase = str_replace("Ü","U",$newphrase);
  	$newphrase = str_replace("Ğ","G",$newphrase);
  	$newphrase = str_replace("Ş","S",$newphrase);
  	$newphrase = str_replace("İ","I",$newphrase);
  	$newphrase = str_replace("Ö","O",$newphrase);
  	$newphrase = str_replace("Ç","C",$newphrase);
  	$newphrase = str_replace("ü","u",$newphrase);
  	$newphrase = str_replace("ğ","g",$newphrase);
  	$newphrase = str_replace("ş","s",$newphrase);
  	$newphrase = str_replace("ı","i",$newphrase);
  	$newphrase = str_replace("ö","o",$newphrase);
  	$newphrase = str_replace("ç","c",$newphrase);


  	$newphrase = str_replace("Ù","U",$newphrase);
  	$newphrase = str_replace("Ğ","G",$newphrase);
  	$newphrase = str_replace("Ş","S",$newphrase);
  	$newphrase = str_replace("İ","I",$newphrase);
  	$newphrase = str_replace("Ö","O",$newphrase);
  	$newphrase = str_replace("Ç","C",$newphrase);
  	$newphrase = str_replace("ü","u",$newphrase);
  	$newphrase = str_replace("ğ","g",$newphrase);
  	$newphrase = str_replace("ş","s",$newphrase);
  	$newphrase = str_replace("ı","i",$newphrase);
  	$newphrase = str_replace("ö","o",$newphrase);
  	$newphrase = str_replace("ç","c",$newphrase);

  	$newphrase = str_replace("%u015F","s",$newphrase);
  	$newphrase = str_replace("%E7","c",$newphrase);
  	$newphrase = str_replace("%FC","u",$newphrase);
  	$newphrase = str_replace("%u0131","i",$newphrase);
  	$newphrase = str_replace("%F6","o",$newphrase);
  	$newphrase = str_replace("%u015E","S",$newphrase);
  	$newphrase = str_replace("%C7","C",$newphrase);
  	$newphrase = str_replace("%DC","U",$newphrase);
  	$newphrase = str_replace("%D6","O",$newphrase);
  	$newphrase = str_replace("%u0130","I",$newphrase);
  	$newphrase = str_replace("%u011F","g",$newphrase);
  	$newphrase = str_replace("%u011E","G",$newphrase);

  	$newphrase = str_replace("£","E",$newphrase);
  	$newphrase = str_replace("é","e",$newphrase);
  	$newphrase = str_replace("è","e",$newphrase);
  	$newphrase = str_replace("ß","x",$newphrase);

  	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $newphrase = utf8_decode($newphrase);    
    $newphrase = strtr($newphrase, utf8_decode($a), $b);

  	$newphrase = strtr($newphrase, "Ã Ã¢Ã¤Ã§Ã©Ã¨ÃªÃ«Ã®Ã¯Ã´Ã¶Ã»Ã¼","aaaceeeeiioouu");

    return $newphrase;
  }
  
  public static function getIP(){
   		if(!empty($_SERVER['HTTP_CLIENT_IP']))
   			$ip=$_SERVER['HTTP_CLIENT_IP'];
   		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
   		else
   			$ip=$_SERVER['REMOTE_ADDR'];
   		return $ip;
   	}
  
  private function __construct(){}
  private function __clone(){}
}

?>