<?php
namespace Framework;
use Framework\Exception\FilenameNotFoundException;


class Photo {
   
   private $filename;
   private $image = false;
   private $type;
   private $width;
   private $height;
   private $cache_dir;
   private $cache_file = false;
   private $last_modified_date;
   private $caching = true;
   
   public function __construct($file, $caching = true){
    $this->filename = $file;
    $this->cache_dir = PHOTO_CACHE_DIR;
    $this->caching = $caching;
    //$this->caching = false;
   }
   
   private function setLastModifiedDate(){
    $this->last_modified_date = date ("d-m-Y H:i:s", filemtime($this->filename));
   }
    
   public function load($filename = false) {
    if($filename === false)
      $filename = $this->filename;
   
    if($this->image === false){

      if(!file_exists($filename))
        throw new FilenameNotFoundException('File could not be found: '.$filename);
        
      $image_info = getimagesize($filename);
      $this->type = $image_info[2];
      $this->width = $image_info[0];
      $this->height = $image_info[1];
      $this->setLastModifiedDate(); 
                   
      if( $this->type == IMAGETYPE_JPEG )
         $this->image = imagecreatefromjpeg($filename);
      elseif( $this->type == IMAGETYPE_GIF )
         $this->image = imagecreatefromgif($filename);
      elseif( $this->type == IMAGETYPE_PNG ){
        $this->image = imagecreatefrompng($filename);
      }
           
    }
   }
   
   public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
     if(!is_dir($this->cache_dir)){
       mkdir($this->cache_dir);
     }
     
      if( $this->type == IMAGETYPE_JPEG )
         imagejpeg($this->image,$filename,$compression);
      elseif( $this->type == IMAGETYPE_GIF )
         imagegif($this->image,$filename);         
     elseif( $this->type == IMAGETYPE_PNG )
         imagepng($this->image,$filename);
      if( $permissions != null)
         chmod($filename,$permissions);
   }
   
   public function output($compression=75,$image_type=IMAGETYPE_JPEG) {
      if($this->cache_file === false){
        if( $this->type == IMAGETYPE_JPEG ){
          header("Content-type: image/jpg");
          imagejpeg($this->image,NULL,$compression);
        }         
        elseif( $this->type == IMAGETYPE_GIF ){
          header("Content-type: image/gif");
          imagegif($this->image);
        }             
        elseif( $this->type == IMAGETYPE_PNG ){
          header("Content-type: image/png");
          imagepng($this->image);
        }
      }else{
        header("Content-type: image/png");
        readfile($this->cache_file);
      }
       
   }
   
   public function getWidth() {
    $this->load();
    return $this->width;
   }
   
   public function getHeight() {
    $this->load();
    return $this->height;
   }
   
   private function PreallocateTransparency(&$image,$width,$height){
     imagealphablending($image, false);
     imagesavealpha($image, true);
     $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
     imagefilledrectangle($image, 0, 0,  $width ,  $height, $transparent);
   }
   
   public function resizeToHeight($height) {
    $hash = md5('resizeToHeight'.$height.$this->filename.$this->last_modified_date);

    if(file_exists($this->cache_dir.$hash) && $this->caching === true){
     $this->cache_file = $this->cache_dir.$hash;
     return true;
    }
    
    $this->load();
    $ratio = $height / $this->getHeight();
    $width = $this->getWidth() * $ratio;
    $new_image = imagecreatetruecolor($width, $height);
    
    if($this->type == IMAGETYPE_PNG){
      $this->PreallocateTransparency($new_image,$width,$height);
    }
    
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
    $this->save($this->cache_dir.$hash);
   }
   
   public function resizeToWidth($width) {
    $hash = md5('resizeToWidth'.$width.$this->filename.$this->last_modified_date);
    
    if(file_exists($this->cache_dir.$hash) && $this->caching === true){
     $this->cache_file = $this->cache_dir.$hash;
     return true;
    }
    
    $this->load();
    $ratio = $width / $this->getWidth();
    $height = $this->getheight() * $ratio;
    $new_image = imagecreatetruecolor($width, $height);
    
    if($this->type == IMAGETYPE_PNG){
      $this->PreallocateTransparency($new_image,$width,$height);
    }
    
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
    $this->save($this->cache_dir.$hash);
  }
   
   public function scale($scale) {
    $hash = md5('scale'.$scale.$this->filename.$this->last_modified_date);
    
    if(file_exists($this->cache_dir.$hash) && $this->caching === true){
     $this->cache_file = $this->cache_dir.$hash;
     return true;
    }
    
    $this->load();
    $width = $this->getWidth() * $scale/100;
    $height = $this->getheight() * $scale/100; 
    $new_image = imagecreatetruecolor($width, $height);
    
    if($this->type == IMAGETYPE_PNG){
      $this->PreallocateTransparency($new_image,$width,$height);
    }
    
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
    $this->save($this->cache_dir.$hash);
   }
   
   public function resize($width,$height) {
    $hash = md5('resize'.$width.$height.$this->filename.$this->last_modified_date);

    if(file_exists($this->cache_dir.$hash) && $this->caching === true){
     $this->cache_file = $this->cache_dir.$hash;
     return true;
    }
    
    $this->load();
    $new_image = imagecreatetruecolor($width, $height);
    
    if($this->type == IMAGETYPE_PNG){
      $this->PreallocateTransparency($new_image,$width,$height);
    }    
    
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->image = $new_image;   
    $this->save($this->cache_dir.$hash);
   }      
   
   public function crop($width,$height,$offsetx,$offsety){
    $hash = md5('crop'.$width.$height.$offsetx.$offsety.$this->filename.$this->last_modified_date);
    
    if(file_exists($this->cache_dir.$hash) && $this->caching === true){
     $this->cache_file = $this->cache_dir.$hash;
     return true;
    }
   
    $this->load();
    
    if(file_exists($this->cache_dir.$hash)){
      $this->cache_file = $this->cache_dir.$hash;
      return true;
     }
    
    $new_image = imagecreatetruecolor( $width, $height);
    
    if($this->type == IMAGETYPE_PNG){
      $this->PreallocateTransparency($new_image,$width,$height);
    }
    
    imagecopyresampled($new_image, $this->image, 0, 0, $offsetx, $offsety, $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight());
    $this->image = $new_image;
    $this->save($this->cache_dir.$hash);
   }
   
   public function cropNewSize($width,$height,$nwidth,$nheight,$offsetx,$offsety){
     $hash = md5('cropNewSize'.$width.$height.$nwidth.$nheight.$offsetx.$offsety.$this->filename.$this->last_modified_date);
     
     if(file_exists($this->cache_dir.$hash) && $this->caching === true){
      $this->cache_file = $this->cache_dir.$hash;
      return true;
     }
     
     $this->load();
    
     $new_image = imagecreatetruecolor( $width, $height);
     
     if($this->type == IMAGETYPE_PNG){
       $this->PreallocateTransparency($new_image,$width,$height);
     }
     
     imagecopyresampled($new_image, $this->image, 0, 0, $offsetx, $offsety, $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight());
     $dest = imagecreatetruecolor($nwidth,$nheight); 

     if($this->type == IMAGETYPE_PNG){
       $this->PreallocateTransparency($dest,$nwidth,$nheight);
     }
     
     imagecopyresampled($dest,$new_image,0,0,0,0,$nwidth,$nheight,$width,$height);
     $this->image = $dest;
     $this->save($this->cache_dir.$hash);
    }
}
?>