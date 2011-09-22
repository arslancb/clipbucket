<?php

/**
 * It is better to keep everything sperated
 * and code properly
 * so i created this file coz i like it this way :p
 *
 *
 * Author : Arslan
 */
 
 

class CBEmail
{
	var $smtp = false;
	var $db_tpl = 'email_templates';
	
	function cbemail()
	{
		//Constructor - do nothing
	}

	/**
	 * Function used to get email tempalate from database
	 */
	function get_email_template($code)
	{
		global $db;
		$result = $db->select(tbl($this->db_tpl),"*"," email_template_code='".$code."' OR email_template_id='$code' ");
		if($db->num_rows>0)
		{
			$result[0]['email_template'] = stripslashes($result[0]['email_template']);
			$result[0]['email_template_subject'] = stripslashes($result[0]['email_template_subject']);
			return $result[0];
		}else
			return false;
	}
	function get_template($code)
	{
		return $this->get_email_template($code);
	}
	
	/**
	 * Check template exists or not
	 */
	function template_exists($code)
	{
		return $this->get_email_template($code);
	}
	
	
	/**
	 * Function used to replace content
	 * of email template with variables
	 * it can either be email subject or message content
	 * @param : Content STRING 
	 * @param : array ARRAY => array({somevar}=>$isvar)
	 */
	function replace($content,$array)
	{
		//Common Varialbs
		
		$com_array = array
		('{website_title}'	=> TITLE,
		 '{baseurl}'		=> BASEURL,
		 '{website_url}'	=> BASEURL,
		 '{date_format}'	=> cbdate(DATE_FORMAT),
		 '{date}'			=> cbdate(),
		 '{username}'		=> username(),
		 '{userid}'			=> userid(),
		 '{date_year}'		=> cbdate("Y"),
		 '{date_month}'		=> cbdate("m"),
		 '{date_day}'		=> cbdate("d"),
		 '{signup_link}'	=> cblink(array('name'=>'signup')),
		 '{login_link}'		=> cblink(array('name'=>'login')),
		 );
		
		if(is_array($array) && count($array)>0)
			$array = array_merge($com_array,$array);
		else 
			$array = $com_array;
		foreach($array as $key => $val)
		{
			$var_array[] = '/'.$key.'/';
			$val_array[] = $val;
		}
		return preg_replace($var_array,$val_array,$content);
	}
	
	/**
	 * Function used to get all templates
	 */
	function get_templates()
	{
		global $db;
		$results = $db->select(tbl($this->db_tpl),"*",NULL,NULL," email_template_name DESC");
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	
	/**
	 * Function used to update email template
	 */
	function update_template($params)
	{
		global $db;
		$id = mysql_clean($params['id']);
		$subj = mysql_clean($params['subj']);
		$msg = mysql_real_escape_string($params['msg']);
		
		if(!$this->template_exists($id))
			e(lang("email_template_not_exist"));
		elseif(empty($subj))
			e(lang("email_subj_empty"));
		elseif(empty($msg))
			e(lang("email_msg_empty"));
		else
		{
			$db->update(tbl($this->db_tpl),array("email_template_subject","email_template"),array($subj,'|no_mc|'.$msg),
									" email_template_id='$id'");
			e(lang("email_tpl_has_updated"),"m");
		}
		
	}
	
	/**
	 * Mass Email
	 */
	function add_mass_email($array=NULL)
	{
		if(!$array)
			$array = $_POST;
		
		global $userquery,$db;
		
		$from = $array['from']; 		unset($array['from']);
		$loop = $array['loop_size']; 	
		$subj = $array['subject']; 		unset($array['subject']);
		$msg  = $array['message']; 		unset($array['message']);
		$users = $array['users']; 		unset($array['users']);
		$method = $array['method']; 	unset($array['method']);
		
		$settings = $array;
		
		unset($array);
		
		if(!isValidEmail($from))
			e(lang("Please enter valid email in 'from' field"));
		if(!is_numeric($loop) || $loop <1 || $loop>10000)
			e(lang("Please enter valid numeric value from 1 to 10000 for loop size"));
		if(!$subj)
			e(lang("Please enter a valid subject for your email"));
		if(!$msg)
			e(lang("Email body was empty, please enter your email content"));
		
		if(!error())
		{
			$db->insert(tbl('mass_emails'),array('email_subj','email_from','email_msg','configs','users','method','status','date_added'),
			array($subj,$from,'|no_mc|'.$msg,'|no_mc|'.json_encode($settings),$users,$method,'pending',now()));
			
			e("Mass email has been added","m");
			return true;
		}else
			return false;		
	}
	
	
	/**
	 * function used to get email
	 */
	function get_mass_emails()
	{
		global $db;
		$results = $db->select(tbl("mass_emails"),"*");

		if($db->num_rows>0)
		{
			return $results;
		}else
			return false;
	}
	
	/**
	 * function used to delete, send emails
	 */
	function action($id,$action)
	{
		global $db;
		$email = $this->email_exists($id);
		if(!$email)
		{
			e(lang("Email does not exist"));
			return false;
		}
		
		switch($action)
		{
			case "delete":
			{
				$db->Execute("DELETE FROM ".tbl('mass_emails')." WHERE id='$id'");
				e(lang("Email has been deleted"),"m");
			}
			break;
			
			case "send_email":
			{
				$this->send_emails($email);
			}
			break;
		}
	}
	
	/**
	 * functionn used to check email exists or not
	 */
	function get_email($id)
	{
		global $db;
		$result = $db->select(tbl("mass_emails"),"*","id='$id'");
		if($db->num_rows>0)
		{
			return $result[0];
		}else
		{
			return false;
		}
	}
	function email_exists($id){ return $this->get_email($id); }
	
	
	
	/**
	 * function send emails
	 */
	function send_emails($id)
	{
		global $db,$userquery,$cbemail;
		if(!is_array($id))
		{
			$email = $this->get_email($id);
		}else
		{
			$email = $id;
		}
			
		if($email['status']=='completed')
			return false;
		$settings = json_decode($email['configs'],true);
		$users = $email['users'];
		$total = $email['total'];
		
		//Creating limit
		$start_index = $email['start_index'];
		$limit = $start_index.','.$settings['loop_size'];
		
		//Creating condition
		$condition = "";
		
		//Levels
		$level_query = "";
		$levels = $settings['level'];
		if($levels)
		{
			foreach($levels as $level)
			{
				if($level_query)
					$level_query .= " OR ";
				$level_query .= " level='$level' ";
			}
			
			if($condition)
				$condition .= " AND ";
			$condition = $level_query = " ( ".$level_query.") ";
		}
		
		//Categories
		$cats_query = "";
		$cats = $settings['cat'];	
		if($cats)
		{
			foreach($cats as $cat)
			{
				if($cats_query)
					$cats_query .= " OR ";
				$cats_query .= " category='$cat' ";
			}
			
			$cats_query = " ( ".$cats_query.") ";
			if($condition)
				$condition .= " AND ";
			$condition .= $cats_query;
		}
		
		
		//Ative users
		if($settings['active']!='any')
		{
			if($condition)
				$condition .= " AND ";
			
			if($settings['active']=='yes')
				$condition .= "	usr_status = 'Ok' ";
			if($settings['active']=='no')
				$condition .= "	usr_status = 'ToActivate' ";
		}
		
		//Banned users
		if($settings['ban']!='any')
		{
			if($condition)
				$condition .= " AND ";
			
			if($settings['ban']=='yes')
				$condition .= "	ban_status = 'yes' ";
			if($settings['ban']=='no')
				$condition .= "	ban_status = 'no' ";
		}
		
		
				
		if(!$users)
		{
			$users = $db->select(tbl("users"),"*",$condition,$limit," userid ASC ");
			
			if(!$total)
			{
				$total = $db->count(tbl("users"),"userid",$condition);
			}
			
			$sent = $email['sent'];			
			$send_msg = array();
			foreach($users as $user)
			{
				$var = array
				('{username}'	=> $user['username'],
				 '{userid}'		=> $user['userid'],
				 '{email}'		=> $user['email'],
				 '{datejoined}'		=> $user['doj'],
				 '{avcode}'		=> $user['avcode'],
				 '{avlink}'		=> BASEURL.'/activation.php?av_username='.$user['username'].'&avcode='.$user['avcode'],
				);
				$subj = $cbemail->replace($email['email_subj'],$var);
				$msg = nl2br($cbemail->replace($email['email_msg'],$var));
				
				$send_message = "";
				
				//Now Finally Sending Email
				cbmail(array('to'=>$user['email'],'from'=>$email['from'],'subject'=>$subj,'content'=>$msg));
				$sent++;
				
				$send_msg[] = $user['userid'].": Email has been sent to <strong><em>".$user['username']."</em></strong>";
				
			}
			
			$sent_to = $start_index+$settings['loop_size'];
			
			if($sent_to>$total)
				$sent_to = $total;
				
			e(sprintf(lang("Sending email from %s to %s"),$start_index+1,$sent_to),"m");
			
			$start_index = $start_index+$settings['loop_size'];
			
			if($sent==$total || $sent>$total)
				$status = 'completed';
			else
				$status = 'sending';
				
			$db->update(tbl('mass_emails'),array('sent','total','start_index','status','last_update'),
			array($sent,$total,$start_index,$status,now())," id='".$email['id']."' ");
			
			return $send_msg;
			
		}
	}
}

?>