<?php
namespace Framework\Cache;

interface CacheInterface{
  
  public function setNamespaceKey($x);
  
  public function getData($sKey);
  
  public function setData($sKey, $vData, $ttl = false) ;
  
  public function deleteData($sKey);
  
  public function getNamespacedKey($key);
}

?>