<?php 
namespace Framework\HTTP;

class Request implements RequestInterface{

  /*
  private $controller;
  private $action;
  private $language;
  private $app;
  
  private $app_name;
  private $extra_params;
  private $params;
  */
  
  public $Get;
  public $Post;
  public $Cookie;
  public $Files;
  public $Server;
  
  public function __construct(
                Data\Get $Get = null,
                Data\Post $Post = null,
                Data\Cookie $Cookie = null,
                Data\Files $Files = null,
                Data\Server $Server = null
                )
  {

    if($Server == null){
      $Server = new Data\Server();
    }
      
    if($Get == null){
      $Get = new Data\Get();
    }
      
    if($Post == null){
      $Post = new Data\Post();
    }
      
    if($Cookie == null){
      $Cookie = new Data\Cookie();
    }
    
    if($Files == null){
      $Files = new Data\Files();
    }
    
    $this->Get = $Get;
    $this->Post = $Post;
    $this->Cookie = $Cookie;
    $this->Files = $Files;
    $this->Server = $Server;
    
    $this->determineParams();
  }
  

  public function getGet()
  {
    return $this->Get;
  }
  
  public function setGet(Data\Get $Get)
  {
    $this->Get = $Get;
  }
  
  public function getPost()
  {
    return $this->Post;
  }
  
  public function setPost(Data\Post $Post)
  {
    $this->Post = $Post;
  }
  
  public function getCookie()
  {
    return $this->Cookie;
  }
  
  public function setCookie(Data\Post $Cookie)
  {
    $this->Cookie = $Cookie;
  }
  
  public function setFiles(Data\Files $Files)
  {
    $this->Files = $Files;
  }
  
  public function getFiles()
  {
    return $this->Files;
  }
  
  public function setServer(Data\Server $Server)
  {
    $this->Server = $Server;
  }
  
  public function getServer()
  {
    return $this->Server;
  }
  
  public function determineParams()
  {
    $query_string = $this->Server->get('QUERY_STRING');
    
    if(substr($query_string,0,1) == '/'){
      $query_string = substr($query_string,1);
    }
    
	  $params =  explode('/', $query_string);
  	if(end($params) == ''){
  	  array_pop($params);
  	}
  	$this->params = $params;
	}
	
	public function getParams()
	{
	  
	  return $this->params;
	}
	
	public function getParam($key)
	{
	  return trim($this->params[$key]) == '' ? false : trim($this->params[$key]);
	}

}