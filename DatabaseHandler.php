<?php
namespace Lib;
/************************************************
** Database handling module for mysql.
 ************************************************/
class DatabaseHandler
{  
  var $dbserver;
  var $dbname;
  var $dbusername;
  var $dbpassword;
  var $connection;
  var $result;
  
  /*************************************
  ** Constructor
  ** @param - string : servername
  ** @param - string : database name
  ** @param - string : database username
  ** @param - string : database password
  *************************************/
  function __construct($DBserver, $DBname, $DBusername, $DBpassword)
  {
    $this->dbserver = $DBserver;
    $this->dbname = $DBname;
    $this->dbusername = $DBusername;
    $this->dbpassword = $DBpassword;
    $this->connect(); //Connect to database
  }
  
  /*************************************
  ** Execute a query where you expect a result.
  ** @return - array : mysql result(s)
  *************************************/
  function executeQuery($query)
  {
    $result_array = array();
	
	if(ENVIRONMENT == 'test'){
    	$this->result = mysql_query($query, $this->connection)
			or die(mysql_error());
	}else{
		$this->result = mysql_query($query, $this->connection)
			or die("Whooooops hier liep iets fout, dit staat in de log ;-)");
	}
	
    $numrows = mysql_num_rows($this->result);
    
    for($i=0; $i < $numrows; $i++)
    {
      $array = mysql_fetch_array($this->result);
      $result_array[] = $array;
    }
    
    return $result_array;
  }
  
  /*************************************
  ** Free used mysql resources.
  *************************************/
  function freeResult() 
  {
    mysql_free_result($this->result);
  }
  
  /*************************************
  ** Execute a query where you dont expect a result.
  *************************************/
  function executeUpdate($update)
  { 
    return mysql_query($update, $this->connection)
      or die(mysql_error());
  }
  
  /*************************************
  ** Return the last ID from after an insert.
  ** There has to be an autoincrement field.
  *************************************/
  function lastInsertId() 
  {
    return mysql_insert_id($this->connection);
  }
  
  function affectedRows()
  { 
    return mysql_affected_rows($this->connection);
  }

  /*************************************
  ** Return the number of rows in the result.
  *************************************/  
  function numRows() {
    return mysql_num_rows($this->result);
  }
 
   /*************************************
  ** Get link to mysql resource.
  **************************************/
  function &getConnection()
  {
    return $this->connection;
  }
  
  /*************************************
  ** Connect to mysql database.
  *************************************/
  function connect() 
  {
    $this->connection = mysql_pconnect($this->dbserver, $this->dbusername, $this->dbpassword)
                      or die ("Could not connect to database");
    mysql_select_db($this->dbname, $this->connection)
      or die ("Could not select database");
    $this->executeUpdate("SET NAMES '".DB_CHARSET."';");
  }
  
  /*************************************
  ** Disconnect mysql database.
  *************************************/
  function disconnect() 
  {
    mysql_close($this->connection);
  }
}
?>
