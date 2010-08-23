<?php
/**
 * Rally simple session class
 * Author Arslan Hassan <arslan@clip-bucket.com>
 * http://clip-bucket.com/
 */


class Session
{
	var $tbl = 'sessions';
	var $id= '';
	var $overwrite = false;
	/**
	 * offcourse, its a constructor
	 */
	function session()
	{
		$this->id = session_id() ;
	}
	
	/**
	 * Function used to add session
	 */
	
	function add_session($user,$name,$value=false)
	{
		global $db;
		if(!$value)
			$value = $this->id;
		$this->get_user_session($user,$name);
		if($db->num_rows>0)
		$db->delete(tbl($this->tbl),array('session_string'),array($name));
		$db->insert(tbl($this->tbl),array('session_user','session_string','session_value'),array($user,$name,$value));
		//Finally Registering session
		$this->session_register($name);
		$this->session_val($name,$value);
	}
	
	
	
	/**
	 * Function is used to get session
	 */
	function get_user_session($user,$session_name=false)
	{
		global $db;
		if($session_name)
			$session_cond = " session_string='".mysql_clean($session_name)."'";
		$results = $db->select(tbl($this->tbl),'*',$session_cond);
		return $results;
	}
	
	 
	 
	/**
	 * Function used to get current user session, if any
	 */
	function get_current_session($session_string)
	{
		global $db;
		$results = $db->select(tbl($this->tbl),'*'," session_string='logged_in' AND session_value='".$this->session."'");
		return $results[0];
	}
	
	
	
	/**
	 Functin used to register session
	 */
	function session_register($name)
	{
		if($this->overwrite)
			$this->session_unregister($name);
		//session_register($name);
	}
	
	/**
	 * FUnction used to unregiser session
	 */
	function session_unregister($name)
	{
		//session_unregister($name);
	}
	
	/**
	 * Ftunction used to set session value
	 */
	function session_val($name,$value)
	{
		$_SESSION[$name] = $value;
	}
	
	/**
	 * Function used to remove session
	 */
	function remove_session($user,$name)
	{
		global $db;
		$db->delete(tbl('sessions'),array("session_user","session_string"),array($user,$name));
		$_SESSION[$name] = '';
		$this->session_unregister($name);
	}
	
	/**
	 * Function used to set register session and set its value
	 */
	function set_session($name,$val)
	{
		$this->session_register($name);
		$_SESSION[$name] = $val;
	}
	function set($name,$val)
	{
		$this->set_session($name,$val);
	}
	
	/**
	 * Function used to remove session value
	 */
	function unset_session($name)
	{
		unset($_SESSION[$name]);
	}
	function un_set($name)
	{
		return $this->unset_session($name);
	}
	
	/**
	 * Function used to get session value
	 * param VARCHAR name
	 */
	function get_session($name)
	{
		if(isset($_SESSION[$name]))
		return $_SESSION[$name];
	}
	//replica
	function get($name){ return $this->get_session($name); }
	
	/**
	 * Destroy Session
	 */
	function destroy()
	{
		session_destroy();
	}
}

?>