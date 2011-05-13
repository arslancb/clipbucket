<?php

/**
 * This class is used to perform
 * Add to favorits
 * Flag content
 * share content
 * rate content
 * playlist 
 * quicklist
 * 
 * @Author : ARSLAN HASSAN (te haur kaun o sukda)
 * @Script : ClipBucket v2
 * @Since : Bakra Eid 2009
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
	var $playlist_tbl = 'playlists';
	var $playlist_items_tbl = 'playlist_items';
	
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
	var $report_opts = array();
	
	
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
	 * initializing
	 */
	function init()
	{
		$this->report_opts = array
		(
		lang('inapp_content'),
		lang('copyright_infring'),
		lang('sexual_content'),
		lang('violence_replusive_content'),
		lang('spam'),
		lang('disturbing'),
		lang('other')		
		);
	}

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
					$db->insert(tbl($this->fav_tbl),array('type','id','userid','date_added'),array($this->type,$id,userid(),NOW()));
					addFeed(array('action'=>'add_favorite','object_id' => $id,'object'=>'video'));
					
					//Loggin Favorite			
					$log_array = array
					(
					 'success'=>'yes',
					 'details'=> "added ".$this->name." to favorites",
					 'action_obj_id' => $id,
					 'action_done_id' => $db->insert_id(),
					);
					insert_log($this->name.'_favorite',$log_array);
					
					e(sprintf(lang('add_fav_message'),$this->name),'m');
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
		$results = $db->select(tbl($this->fav_tbl),"favorite_id"," id='".$id."' AND userid='".$uid."' AND type='".$this->type."'");
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
					$db->insert(tbl($this->flag_tbl),array('type','id','userid','flag_type','date_added'),
												array($this->type,$id,userid(),mysql_clean(post('flag_type')),NOW()));
					e(sprintf(lang('obj_report_msg'),$this->name),'m');
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
	 * Function used to delete flags
	 */
	function delete_flags($id)
	{
		global $db;
		$db->delete(tbl($this->flag_tbl),array("id","type"),array($id,$this->type));
		e(sprintf(lang("type_flags_removed"),$this->name),"m");
	}
	
	
	/**
	 * Function used to check weather user has already reported the object or not
	 */
	function report_check($id)
	{
		global $db;
		$results = $db->select(tbl($this->flag_tbl),"flag_id"," id='".$id."' AND type='".$this->type."' AND userid='".userid()."'");
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
			global $eh;
			if(userid())
			{
				
				$post_users = mysql_clean(post('users'));
				$users = explode(',',$post_users);
				if(is_array($users) && !empty($post_users))
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
						$from = $userquery->get_user_field_only(username(),"email");
						
						cbmail(array('to'=>$emails_array,'from'=>$from,'from_name'=>username(),'subject'=>$subj,'content'=>$msg,'use_boundary'=>true));
						e(sprintf(lang("thnx_sharing_msg"),$this->name),'m');
						
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
	function get_favorites($params)
	{
		global $db;
		
		$uid	= $params['userid'];
		$limit	= $params['limit'];
		$cond	= $params['cond'];
		$order	= $params['order'];
		
		if(!$uid)
			$uid=userid();
		if($cond)
			$cond = " AND ".$cond;
		
		if(!$params['count_only'])
		{
			$results = $db->select(tbl($this->fav_tbl.",".$this->type_tbl),"*"," ".tbl($this->fav_tbl).".type='".$this->type."' 
							   AND ".tbl($this->fav_tbl).".userid='".$uid."' 
							   AND ".tbl($this->type_tbl).".".$this->type_id_field." = ".tbl($this->fav_tbl).".id".$cond,$limit,$order);
		}
		if($params['count_only'])
		{
			return $results = $db->count(tbl($this->fav_tbl.",".$this->type_tbl),"*"," ".tbl($this->fav_tbl).".type='".$this->type."' 
							   AND ".tbl($this->fav_tbl).".userid='".$uid."' 
							   AND ".tbl($this->type_tbl).".".$this->type_id_field." = ".tbl($this->fav_tbl).".id".$cond);
		}
		
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	/**
	 * Function used to count total favorites only
	 */
	function total_favorites()
	{
		global $db;
		return $db->count(tbl($this->fav_tbl),"favorite_id"," type='".$this->type."'");
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
			$db->delete(tbl($this->fav_tbl),array('userid','type','id'),array($uid,$this->type,$fav_id));
			e(sprintf(lang('fav_remove_msg'),$this->name),'m');
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
		$results = $db->select(tbl($this->flag_tbl.",".$this->type_tbl),"*,
							   count(*) AS total_flags",tbl($this->flag_tbl).".id = ".tbl($this->type_tbl).".".$this->type_id_field." 
							   AND type='".$this->type."' GROUP BY ".tbl($this->flag_tbl).".id ,".tbl($this->flag_tbl).".type ",$limit);				   
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	/**
	 * Function used to get all flags of an object
	 */
	function get_flags($id)
	{
		global $db;
		$type = $this->type;
		$results = $db->select(tbl($this->flag_tbl),"*","id = '$id' AND type='".$this->type."'");
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
		$results = $db->select(tbl($this->flag_tbl.",".$this->type_tbl),"id",tbl($this->flag_tbl).".id = ".tbl($this->type_tbl).".".$this->type_id_field." 
							   AND type='".$this->type."' GROUP BY ".tbl($this->flag_tbl).".id ,".tbl($this->flag_tbl).".type ");
		if($db->num_rows>0)
			return count($results);
		else
			return 0;
	}
	
	
	/**
	 * Function used to create new playlist
	 * @param ARRAY
	 */
	function create_playlist($params)
	{
		global $db;
		$name = mysql_clean($params['name']);
		if(!userid())
			e(lang("please_login_create_playlist"));
		elseif(empty($name))
			e(lang("please_enter_playlist_name"));
		elseif($this->playlist_exists($name,userid(),$this->type))
			e(sprintf(lang("play_list_with_this_name_arlready_exists"),$name));
		else
		{
			$db->insert(tbl($this->playlist_tbl),array("playlist_name","userid","date_added","playlist_type"),
									  array($name,userid(),now(),$this->type));
			e(lang("new_playlist_created"),"m");
			$pid = $db->insert_id();
			
			//Logging Playlist			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "created playlist",
			 'action_obj_id' => $pid,
			);
			
			insert_log('add_playlist',$log_array);
					
			return $pid;
		}
		
		return false;
	}
	
	/**
	 * Function used to check weather playlist already exists or not
	 */
	function playlist_exists($name,$user,$type=NULL)
	{
		global $db;
		if($type)
			$type = $this->type;
		$count = $db->count(tbl($this->playlist_tbl),"playlist_id"," userid='$user' AND playlist_name='$name' AND playlist_type='".$type."' ");

		if($count)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get playlist
	 */
	function get_playlist($id,$user=NULL)
	{
		global $db;
		
		$user_cond;
		if($user)
			$user_cond = " AND userid='$user'";
			
		$result = $db->select(tbl($this->playlist_tbl),"*"," playlist_id='$id' $user_cond");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	
	/**
	 * Function used to add new item in playlist
	 */
	function add_playlist_item($pid,$id)
	{
		global $db;
		
		if(!$this->exists($id))
			e(sprintf(lang("obj_not_exists"),$this->name));
		elseif(!$this->get_playlist($pid))
			e(lang("playlist_not_exist"));
		elseif(!userid())
			e(lang('you_not_logged_in'));
		elseif($this->playlist_item_with_obj($id,$pid))
			e(sprintf(lang('this_already_exist_in_pl'),$this->name));
		else
		{
			$db->insert(tbl($this->playlist_items_tbl),array("object_id","playlist_id","date_added","playlist_item_type","userid"),
											array($id,$pid,now(),$this->type,userid()));
			e(sprintf(lang('this_thing_added_playlist'),$this->name),"m");
			return $db->insert_id();
		}
	}
	
	
	/**
	 * Function use to delete playlist item
	 */
	function delete_playlist_item($id)
	{
		global $db;
		$item = $this->playlist_item($id);		
		if(!$item)
			e(lang("playlist_item_not_exist"));
		elseif($item['userid']!=userid() && !has_access('admin_access'))
			e(lang("you_dont_hv_permission_del_playlist"));
		else
		{
			$db->delete(tbl($this->playlist_items_tbl),array("playlist_item_id"),array($id));
			e(lang("playlist_item_delete"),"m");
		}
	}
	
	/**
	 * Function used to check weather playlist item exists or not
	 */
	function playlist_item($id)
	{
		global $db;
		$result = $db->select(tbl($this->playlist_items_tbl),"*"," playlist_item_id='$id' ");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to check weather playlist item exists or not
	 */
	function playlist_item_with_obj($id,$pid=NULL)
	{
		global $db;
		$pid_cond = "";
		if($pid)
			$pid_cond = " AND playlist_id='$pid'";
		$result = $db->select(tbl($this->playlist_items_tbl),"*"," object_id='$id' $pid_cond");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to update playlist details
	 */
	function edit_playlist($params)
	{
		global $db;
		$name = mysql_clean($params['name']);
		$pdetails = $this->get_playlist($params['pid']);
		
		if(!$pdetails)
			e(lang("playlist_not_exist"));
		elseif(!userid())
			e(lang("you_not_logged_in"));
		elseif(empty($name))
			e(lang("please_enter_playlist_name"));
		elseif($this->playlist_exists($name,userid()) && $pdetails['playlist_name'] !=$name)
			e(sprintf(lang("play_list_with_this_name_arlready_exists"),$name));
		else
		{
			$db->update(tbl($this->playlist_tbl),array("playlist_name"),
									  array($name)," playlist_id='".$params['pid']."'");
			e(lang("play_list_updated"),"m");
		}
	}
	
	/**
	 * Function used to delete playlist
	 */
	function delete_playlist($id)
	{
		global $db;
		$playlist = $this->get_playlist($id);
		if(!$playlist)
			e(lang("playlist_not_exist"));
		elseif($playlist['userid']!=userid() && !has_access('admin_access',TRUE))
			e(lang("you_dont_hv_permission_del_playlist"));
		else
		{
			$db->delete(tbl($this->playlist_tbl),
						array("playlist_id"),array($id));
			$db->delete(tbl($this->playlist_items_tbl),
						array("playlist_id"),array($id));
			e(lang("playlist_delete_msg"),"m");
		}
	}
	
	/**
	 * Function used to get playlists
	 */
	function get_playlists()
	{
		global $db;
		$result = $db->select(tbl($this->playlist_tbl),"*"," playlist_type='".$this->type."' AND userid='".userid()."'");
		
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get playlist items
	 */
	function get_playlist_items($pid)
	{
		global $db;
		$result = $db->select(tbl($this->playlist_items_tbl),"*","playlist_id='$pid'");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to count playlist item
	 */	
	function count_playlist_items($id)
	{
		global $db;
		return $db->count(tbl($this->playlist_items_tbl),"playlist_item_id","playlist_id='$id'");
	}
	
	
	/**
	 * Function used to count total playlist or items
	 */
	function count_total_playlist($item=false)
	{
		global $db;
		if(!$item)
		{
			$result = $db->count(tbl($this->playlist_tbl),"*"," playlist_type='".$this->type."' ");
			return $result;
		}else{
			return $db->count(tbl($this->playlist_items_tbl),"playlist_item_id"," playlist_item_type='".$this->type."'");
		}
	}
}

?>