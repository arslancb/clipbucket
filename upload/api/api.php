<?php

require_once("Rest.inc.php");

class API extends REST
{
	public $data = "";
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB = "rest";
	
	private $db = NULL;
	
	public function __construct()
	{
	  parent::__construct();// Init parent contructor
	  //$this->dbConnect();// Initiate Database connection
	}
	
	//Database connection
	private function dbConnect()
	{
	  $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
	  if($this->db)
	  mysql_select_db(self::DB,$this->db);
	}
	
	//Public method for access api.
	//This method dynmically call the method based on the query string
	public function processApi()
	{
	  $func = strtolower(trim(str_replace("/","",$_REQUEST['mode'])));
	  if((int)method_exists($this,$func) > 0)
	  $this->$func();
	  else
	  $this->response('',404);
	// If the method not exist with in this class, response would be "Page not found".
	}
	
	
	
	private function users()
	{
	    // Cross validation if the request method is GET else it will return "Not Acceptable" status
		if($this->get_request_method() != "GET")
		{
		   $this->response('',406);
		}
		$sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1", $this->db);
		if(mysql_num_rows($sql) > 0)
		{
		$result = array();
		while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC))
		{
		$result[] = $rlt;
		}
		// If success everythig is good send header as "OK" and return list of users in JSON format
		$this->response($this->json($result), 200);
		}
		$this->response('',204); // If no records "No Content" status
	}
	
	private function deleteUser()
	{
		if($this->get_request_method() != "DELETE"){
		$this->response('',406);
		}
		$id = (int)$this->_request['id'];
		if($id > 0)
		{
		
		  mysql_query("DELETE FROM users WHERE user_id = $id");
		  
		  if (mysql_affected_rows() > 0) 
          $success = array('status' => "Success", "msg" => "Successfully one record deleted.");
		  else
		  $success = array('status' => "Failure", "msg" => "No such id exist in database");
		
		  $this->response($this->json($success),200);
		}
		else
		{
		  $this->response('',204); // If no records "No Content" status
		}
	}
	
	//Encode array into JSON
	private function json($data)
	{
	   if(is_array($data))
	   {
	     return json_encode($data);
	   }
	}
}

// Initiiate Library
$api = new API;
$api->processApi();
?>