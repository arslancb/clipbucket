<?php

/**
 * This class is used to perform
 * Add to favorits
 * Flag content
 * share content
 * rate content
 */
 


class cbactions
{
	/**
	 * Defines what is the type of content
	 * v = video
	 * p = pictures
	 * g = groups etc
	 */
	 
	var $type = 'v';
	
	/**
	 * Defines whats the name of the object
	 * weather its 'video' , its 'picture' or its a 'group'
	 */
	var $name = 'video';
	
	/**
	 * Defines the database table name
	 * that stores all information about these actions
	 */
	var $fav_tbl = 'favorites';
	var $flag_tbl = 'flags';
	
	var $type_tbl = 'videos';
	var $type_id_field = 'videoid';
	
	/**
	 * Class variable ie $somevar = SomeClass;
	 * $obj_class = 'somevar';
	 */
	var $obj_class = 'cbvideo';
	
	
	/**
	 * Defines function name that is used to check
	 * weather object exists or not
	 * ie video_exists
	 * it will be called as ${$this->obj_class}->{$this->check_func}($id);
	 */
	var $check_func = 'video_exists';
	
	/**
	 * This holds all options that are listed when user wants to report
	 * a content ie - copyrighted content - voilance - sex or something alike
	 * ARRAY = array('Copyrighted','Nudity','bla','another bla');
	 */
	var $report_opts = array('Inappropriate Content','Copyright infringement','Sexual Content','Violance or repulsive content','Spam','Disturbing','Other');
	
	
	/**
	 * share email template name
	 */
	var $share_template_name = 'video_share_template';



	/**
	 * Var Array for replacing text of email templates
	 * see docs.clip-bucket.com for more details
	 */
	var $val_array = array();



	/**
	 * Function used to add content to favorits
	 */
	 
	function add_to_fav($id)
	{
		 global $db;
		 //First checking weather object exists or not
		 if($this->exists($id))
		 {
			if(userid())
			{
				if(!$this->fav_check($id))
				{
					$db->insert($this->fav_tbl,array('type','id','userid','date_added'),array($this->type,$id,userid(),NOW()));
					e(sprintf(lang('add_fav_message'),$this->name),m);
				}else{
					e(sprintf(lang('already_fav_message'),$this->name));
				}
			}else{
				e(lang("you_not_logged_in"));
			}
		 }else{
			 e(sprintf(lang("obj_not_exists"),$this->name));
		 }
	 }
	 
	 	 
	function add_to_favorites($id){ return $this->add_to_fav($id); }
	function add_favorites($id){ return $this->add_to_fav($id); }
	function add_fav($id){ return $this->add_to_fav($id); }
	 
	/**
	 * Function used to check weather object already added to favorites or not
	 */
	function fav_check($id,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid =userid();
		$results = $db->select($this->fav_tbl,"favorite_id"," id='".$id."' AND userid='".$uid."' AND type='".$this->type."'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	
	}
	 
	/**
	 * Function used to check weather object exists or not
	 */
	function exists($id)
	{
		$obj = $this->obj_class;
		global ${$obj};
		$obj = ${$obj};
		$func = $this->check_func;
		return $obj->{$func}($id);
	}
	
	
	/**
	 * Function used to report a content
	 */
	 
	function report_it($id)
	{
		global $db;
		//First checking weather object exists or not
		if($this->exists($id))
		{
			if(userid())
			{
				if(!$this->report_check($id))
				{
					$db->insert($this->flag_tbl,array('type','id','userid','flag_type','date_added'),
												array($this->type,$id,userid(),post('flag_type'),NOW()));
					e(sprintf(lang('obj_report_msg'),$this->name),m);
				}else{
					e(sprintf(lang('obj_report_err'),$this->name));
				}
			}else{
				e(lang("you_not_logged_in"));
			}
		}else{
		 e(sprintf(lang("obj_not_exists"),$this->name));
		}
	}
	function flag_it($id){ return $this->report_id($id); }
	
	/**
	 * Function used to check weather user has already reported the object or not
	 */
	function report_check($id)
	{
		global $db;
		$results = $db->select($this->flag_tbl,"flag_id"," id='".$id."' AND userid='".userid()."'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	
	
	/**
	 * Function used to content
	 */
	function share_content($id)
	{
		global $db,$userquery;
		$ok = true;
		$tpl = $this->share_template_name;
		$var = $this->val_array;
		
		//First checking weather object exists or not
		if($this->exists($id))
		{
			if(userid())
			{
				$users = mysql_clean(post('users'));
				$users = explode(',',$users);
				if(is_array($users))
				{
					foreach($users as $user)
					{
						if(!$userquery->user_exists($user) && !isValidEmail($user))
						{
							e(sprintf(lang('user_no_exist_wid_username'),$user));
							$ok = false;
							break;
						}
						
						$email = $user;
						if(!isValidEmail($user))
							$email = $userquery->get_user_field_only($user,'email');
						$emails_array[] = $email;
					}
					
					if($ok)
					{
						global $cbemail;
						$tpl = $cbemail->get_template($tpl);
						$more_var = array
						('{user_message}'	=> post('message'),);
						$var = array_merge($more_var,$var);
						$subj = $cbemail->replace($tpl['email_template_subject'],$var);
						$msg = $cbemail->replace($tpl['email_template'],$var);
						
						//Setting Emails
						$emails = implode(',',$emails_array);
						//Now Finally Sending Email
						cbmail(array('to'=>$emails,'from'=>username(),'subject'=>$subj,'content'=>$msg,'use_boundary'=>true));
					}
				}else{
					e(sprintf(lang("share_video_no_user_err"),$this->name));
				}
					
			}else{
				e(lang("you_not_logged_in"));
			}
		}else{
		 e(sprintf(lang("obj_not_exists"),$this->name));
		}
	}
	
	
	/**
	 * Get Used Favorites
	 */
	function get_favorites($uid=NULL,$limit=NULL,$cond=NULL,$order=NULL)
	{
		global $db;
		if(!$uid)
			$uid=userid();
		if($cond)
			$cond = " AND ".$cond;
		$results = $db->select($this->fav_tbl.",".$this->type_tbl,"*"," ".$this->fav_tbl.".type='".$this->type."' 
							   AND ".$this->fav_tbl.".userid='".$uid."' 
							   AND ".$this->type_tbl.".".$this->type_id_field." = ".$this->fav_tbl.".id".$cond,$limit,$order);
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	
	/**
	 * Function used remove video from favorites
	 */
	function remove_favorite($fav_id,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid=userid();
		if($this->fav_check($fav_id,$uid))
		{
			$db->delete($this->fav_tbl,array('userid','type','id'),array($uid,$this->type,$fav_id));
			e(sprintf(lang('fav_remove_msg'),$this->name),m);
		}else
			e(sprintf(lang('unknown_favorite'),$this->name));
	}
	
	
	/**
	 * Function used to get object flags
	 */
	function get_flagged_objects($limit=NULL)
	{
		global $db;
		$type = $this->type;
		$results = $db->select($this->flag_tbl.",".$this->type_tbl,"*,
							   count(*) AS total_flags",$this->flag_tbl.".id = ".$this->type_tbl.".".$this->type_id_field." 
							   AND type='".$this->type."' GROUP BY ".$this->flag_tbl.".id ,".$this->flag_tbl.".type ",$limit);
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	
	/**
	 * Function used to count object flags
	 */
	function count_flagged_objects()
	{
		global $db;
		$type = $this->type;
		$results = $db->select($this->flag_tbl.",".$this->type_tbl,"id",$this->flag_tbl.".id = ".$this->type_tbl.".".$this->type_id_field." 
							   AND type='".$this->type."' GROUP BY ".$this->flag_tbl.".id ,".$this->flag_tbl.".type ");
		if($db->num_rows>0)
			return count($results);
		else
			return false;
	}
}

?>