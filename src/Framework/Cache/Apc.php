<?php
namespace Framework\Cache;

class Apc implements CacheInterface {

    private $ttl = 127800;
    private $enabled = false;
    public $namespace_key = '';
    
    public function __construct() {
      $this->enabled = extension_loaded('apc');
    }
    
    public function setNamespaceKey($x)
    {
      $this->namespace_key = $x;
    }

    public function getData($sKey) 
    {
      $bRes = false;
      $vData = apc_fetch($this->getNamespacedKey($sKey), $bRes);
      return ($bRes) ? $vData : null;
    }

    public function setData($sKey, $vData, $ttl = false) 
    {
      $ttl = $this->ttl;
      if($ttl !== false && $ttl >= 0)
        $ttl = $ttl;
        
      return apc_store($this->getNamespacedKey($sKey), $vData, $ttl);
    }

    public function deleteData($sKey) 
    {
      $bRes = false;
      apc_fetch($this->getNamespacedKey($sKey), $bRes);
      return ($bRes) ? apc_delete($this->getNamespacedKey($sKey)) : true;
    }
    
    public static function getNamespacedKey($key)
    {
      return $this->namespace_key.'_'.$key;
    }
}

?>