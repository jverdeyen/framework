<?php
namespace Lib;

class Queries{

  private function __construct(){}
  private function __clone(){}
  
  public function getConditionString($value,$var_name,$is_int = false){
    
    if(trim((string)$value) == '' && !is_array($value))
      return '';
    
    if(count($value) <= 0 || !is_bool($is_int))
      throw new \Exception("Invalid condition value and/or var_name (var => $value, var_name => $var_name).");
    
    if(!(strpos('.',$var_name) === false))
      $var_name = '`'.$var_name.'`';  
      
    if(!is_array($value) || (count($value) == 1 && is_array($value)) ){
      
      if(count($value) == 1 && is_array($value))
        $value = array_pop($value);
        
      if($is_int === false){
        $string = " AND $var_name = '$value' ";
      }else{
        if(!is_numeric($value))
          throw new \Exception("Invalid integer value $value.");
        $string = " AND $var_name = $value ";
      }
    }else{
      if($is_int === false){
        $string = " AND ( $var_name IN ('".implode("','",array_unique($value))."'))";
      }else{
        foreach($value as $int){
          if(!is_numeric($int)) throw new \Exception("Invalid integer value $int in ".print_r($value,true).".");
        }
        $string = " AND ( $var_name IN (".implode(",",array_unique($value))."))";
      }
    }
    
    return $string;
  }
  
  public function getOrderString($col_name_order){
    if($col_name_order == '' && !is_array($col_name_order))
      return '';
    
    if(!is_array($col_name_order)){
      $order = 'ASC';
      if(!(strpos(strtoupper($col_name_order),'DESC') === false)){
        $order = 'DESC';
        $var_name = trim(str_replace('-DESC','',strtoupper($col_name_order)));
      }else{
        $var_name = trim(str_replace('-ASC','',strtoupper($col_name_order)));
      }
      
      if(!(strpos('.',$var_name) === false))
        $var_name = '`'.$var_name.'`';
      
      return " ORDER BY $var_name $order";
    }else{
      $string = array();
      foreach($col_name_order as $item){
        $order = 'ASC';
        if(!(strpos(strtoupper($item),'DESC') === false)){
          $order = 'DESC';
          $var_name = trim(str_replace('-DESC','',strtoupper($item)));
        }else{
          $var_name = trim(str_replace('-ASC','',strtoupper($item)));
        }
        
        if(!(strpos('.',$var_name) === false))
          $var_name = '`'.$var_name.'`';
        
        $string[] = " $var_name $order ";
      }
      return 'ORDER BY '.implode(',',$string);
    }
    
  }
  
  public function escape($value){
    $link = Database::getInstance()->getConnection();
    if(is_array($value)){
      foreach($value as $key => $item)
        $value[$key] = mysql_real_escape_string($item, $link);
      return $value;
    }else{
      return mysql_real_escape_string($value,$link);
    }
  }

}