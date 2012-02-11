<?php
namespace Framework\Cache;

class Apc {

    private static $iTtl = 127800;
    private static $bEnabled = false;

    // constructor
    public function __construct() {
      self::$bEnabled = extension_loaded('apc');
    }

    public static function getData($sKey) {
      $bRes = false;
      $vData = apc_fetch(self::getNamespacedKey($sKey), $bRes);
      return ($bRes) ? $vData : null;
    }

    public static function setData($sKey, $vData, $iTtl = false) {
      $ttl = self::$iTtl;
      if($iTtl !== false && $iTtl >= 0)
        $ttl = $iTtl;
        
      return apc_store(self::getNamespacedKey($sKey), $vData, $ttl);
    }

    // delete data from memory
    public static function deleteData($sKey) {
      $bRes = false;
      apc_fetch(self::getNamespacedKey($sKey), $bRes);
      return ($bRes) ? apc_delete(self::getNamespacedKey($sKey)) : true;
    }
    
    public static function getNamespacedKey($key){
      return CACHE_NAMESPACE.'_'.$key;
    }
}

?>