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
	
	//Use cookies over sessions
	var $cookie = true;
	var $timeout = 3600; //1 hour
	
	/**
	 * offcourse, its a constructor
	 */
	function session()
	{
		$this->id = session_id() ;
		$this->timeout  = COOKIE_TIMEOUT;
	}
	
	/**
	 * Function used to add session*/
	function add_session($user,$name,$value=false,$reg=false)
	{
		global $db,$pages;
		if(!$value)
			$value = $this->id;
		
		$this->get_user_session($user,$name,true);
		
		if($db->num_rows>0)
		{
			$db->delete(tbl($this->tbl),array('session_string','session'),array($name,$this->id));
		}
		
		$cur_url = $pages->GetCurrentUrl();
		
		if(THIS_PAGE!='cb_install')
		{
			$db->insert(tbl($this->tbl),array('session_user','session','session_string','ip','session_value','session_date',
			'last_active','referer','agent','current_page'),
			array($user,$this->id,$name,$_SERVER['REMOTE_ADDR'],$value,now(),now(),$_SERVER['HTTP_REFERER'],$_SERVER['HTTP_USER_AGENT'],$cur_url));
		}
		if($reg)
		{
			//Finally Registering session
			$this->session_register($name);
			$this->session_val($name,$value);
		}
	}
	
	
	
	/**
	 * Function is used to get session
	 */
	function get_user_session($user,$session_name=false,$phpsess=false)
	{
		global $db;
		if($session_name)
			$session_cond = " session_string='".mysql_clean($session_name)."'";
		if($phpsess)
		{
			if($session_cond)
				$session_cond .= " AND ";
			$session_cond .= " session ='".$this->id."' ";
		}
		$results = $db->select(tbl($this->tbl),'*',$session_cond);
		return $results;
	}
	
	/**
	 * Function used to get sessins
	 */
	function get_sessions()
	{
		global $db,$pages;
		$results = $db->select(tbl($this->tbl),'*'," session ='".$this->id."' ");
		
		$cur_url = $pages->GetCurrentUrl();
		
		if(THIS_PAGE!='cb_install')
		{
			if(THIS_PAGE!='ajax')
				$db->update(tbl($this->tbl),array("last_active","current_page"),array(now(),$cur_url)," session='".$this->id."' ");
			else
				$db->update(tbl($this->tbl),array("last_active"),array(now())," session='".$this->id."' ");
		}
			
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
		
		if($this->cookie)
		{
			setcookie($name,$val,time()+$this->timeout,'/');
		}else
		{
			$this->session_register($name);
			$_SESSION[$name] = $val;
		}
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
		if($this->cookie)
		{
			unset($_COOKIE[$name]);
			setcookie($name,'',0);
		}else
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
		if($this->cookie)
		{
			if($_COOKIE[$name])
				return $_COOKIE[$name];
		}else
		{
			if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		}
	}
	//replica
	function get($name){ return $this->get_session($name); }
	
	/**
	 * Destroy Session
	 */
	function destroy()
	{
		global $db;
		$db->delete(tbl($this->tbl),array('session'),array($this->id));
		session_destroy();
	}
	
	/**
	 * Function set cookie
	 */
	function set_cookie($name,$val)
	{
		setcookie($name,($val),3600+time(),'/');
	}
	
	/**
	 * Function get cookie
	 */
	function get_cookie($name)
	{
		return stripslashes(($_COOKIE[$name]));
	}
	
	
	function kick($id)
	{
		global $db;
		//Getting little details from sessions such that
		//some lower class user can kick admins out ;)
		$results = $db->select(tbl("sessions")." LEFT JOIN (".tbl("users").") ON 
		(".tbl("sessions").".session_user=".tbl("users").".userid)", tbl("sessions").".*,
		".tbl("users").".level","session_id='".$id."'");
		
		$results = $results[0];
		
		if($results['level']==1)
		{
			e("You cannot kick administrators");
			return false;
		}
		$db->delete(tbl($this->tbl),array("session_id"),array($id));
		return true;
	}
}

?>