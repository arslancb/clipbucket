<?php
/**
 * @Author  : Arslan Hassan, Fawaz Tahir
 * @Software : ClipBucket, Community Bucket 
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : 15 December 2009
 */

class CBGroups extends CBCategory
{
	var $gp_thumb_width = '140';
	var $gp_thumb_height = '140';
	var $gp_small_thumb_width = '60';
	var $gp_small_thumb_height = '60';
	var $gp_tbl = '';
	var $custom_group_fields = array();
	var $actions = '';
	var $group_manager_funcs = array();
	
	/**
	 * Constructor function to set values of tables
	 */
	function CBGroups()
	{
		global $Cbucket;
		$this->cat_tbl = 'group_categories';
		$this->gp_tbl =  'groups';
		$this->gp_mem_tbl =  'group_members';
		//We will using CB Commenting system as posts
		//$this->gp_post_tbl = 'group_posts';
		$this->gp_topic_tbl = 'group_topics';
		$this->gp_invite_tbl = 'group_invitations';
		$this->gp_vdo_tbl = 'group_videos';
		
		//Adding Actions such Report, share,fav etc
		$this->action = new cbactions();
		$this->action->type = 'g';
		$this->action->name = 'group';
		$this->action->obj_class = 'cbgroup';
		$this->action->check_func = 'group_exists';
		$this->action->type_tbl = $this->gp_tbl;
		$this->action->type_id_field = 'group_id';
		
		if(isSectionEnabled('groups'))
		$Cbucket->search_types['groups'] = "cbgroup";

	}
		
	/**
	 * Function used to check if the provided URL is taken or not
	 * @param = $url { URL of group provided by user }
	 */		
	function group_url_exists($url) {
		global $db;
		$result = $db->count(tbl($this->gp_tbl),"*"," group_url='$url'");
		if($result[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get group details
	 * @param $ID { groupid  }
	 */
	function get_group($id)
	{
		global $db;
		$gp_details = $db->select(tbl($this->gp_tbl),"*","group_id='$id'");
		if($db->num_rows>0) {
			return $gp_details[0];
		} else{
			return false;
		}
	}
	function get_group_details($id){ return $this->get_group($id); }
	function get_details($id){ return $this->get_group($id); }
	
	/**
	 * Funtion used to get gorup details
	 * @param, $URL { group url }
	 */
	function get_group_with_url($url)
	{
		global $db;
		$gp_details = $db->select(tbl($this->gp_tbl),"*","group_url='$url'");
		if($db->num_rows>0) {
			return $gp_details[0];
		} else{
			return false;
		}
	}
	function get_group_details_with_url($id){ return $this->get_group_with_url($id); }
	function get_details_with_url($id){ return $this->get_group_with_url($id); }
	function group_details_url($id){ return $this->get_group_with_url($id); }
	
			
	/**
	 * Function used to make user a member of group 
	 * @param = $userid { ID of user who is going to Join Group }
	 * @param = $gpid { ID of group which is being joined }
	 */
	function join_group($gpid,$userid,$createFeed=true) {
		
		global $db;
		
		//Getting group details
		$group = $this->get_group_details($gpid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$this->is_joinable($group,$userid,TRUE))
			return false;
		elseif(!$userid)
			e(lang('group_join_login_err'));
		else
		{	
			if($group['group_privacy']==1 && $group['userid'] != $userid)
				$active = 'no';
			else
				$active = 'yes';
			
			
			$db->insert(tbl($this->gp_mem_tbl),
						array("group_id","userid","date_added","active"),
						array($gpid,$userid,now(),$active));
			
			//Count total members
			$total_members = $this->total_members($gpid);
			
			//Adding Feed
			if($createFeed)
			addFeed(array('action'=>'join_group','object_id' => $gpid,'object'=>'group','userid'=>$userid));
			
			//Update Stats
			$db->update(tbl($this->gp_tbl),
						array("total_members"),
						array($total_members),
						"group_id='$gpid'");
			
			e(lang('grp_join_msg_succ'),'m');
		}
	}
	
	/**
	 * Creating Group Required Fields
	 */
	function load_required_fields($default=NULL,$is_update=FALSE)
	{
		if($default == NULL)
			$default = $_POST;

		$gptitle = $default['group_name'];
		$gpdescription = $default['group_description'];

		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		else
		{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
		}
		
		$tags = $default['group_tags'];
		$gpurl = $default['group_url'];
		
		
		if(!$is_update)
			$url_form = array(
						'title'=> lang('grp_url_title'),
						'type'=> 'textfield',
						'name'=> 'group_url',
						'id'=> 'group_url',
						'value'=> cleanForm($gpurl),
						'hint_1'=> '',
						'hint_2'=> lang('grp_url_msg'),
						'db_field'=>'group_url',
						'required'=>'yes',
						'invalid_err'=>lang('grp_url_error'),
						'syntax_type'=> 'field_text',
						'function_error_msg' => lang('user_contains_disallow_err'),
						'db_value_check_func'=> 'group_url_exists',
						'db_value_exists'=>false,
						'db_value_err'=>lang('grp_url_error2'),
						'min_length' => 3,
						'max_length' => 18,
						
						);
		else
			$url_form = array(
						'title'=> lang('grp_url_title'),
						'type'=> 'textfield',
						'name'=> 'group_url',
						'id'=> 'group_url',
						'value'=> cleanForm($gpurl),
						'hint_1'=> '',
						'hint_2'=> lang('grp_url_msg'),
						'db_field'=>'group_url',
						'required'=>'yes',
						'invalid_err'=>lang('grp_url_error'),
						'syntax_type'=> 'field_text',
						'function_error_msg' => lang('user_contains_disallow_err'),	
						'min_length' => 3,
						'max_length' => 18,
						);
			
		$fields = array
		(
		 'name'	=> array(
						'title'=> lang('grp_name_title'),
						'type'=> "textfield",
						'name'=> "group_name",
						'id'=> "group_name",
						'value'=> $gptitle,
						'db_field'=>'group_name',
						'required'=>'yes',
						'invalid_err'=>lang('grp_name_error'),
						'max_length'=>config('grp_max_title')
						),
		 'tags'		=> array(
						'title'=> lang('tag_title'),
						'type'=> 'textfield',
						'name'=> 'group_tags',
						'id'=> 'group_tags',
						'value'=> (genTags($tags)),
						'hint_1'=> '',
						'hint_2'=> lang('grp_tags_msg1'),
						'db_field'=>'group_tags',
						'required'=>'yes',
						'invalid_err'=>lang('grp_tags_error'),
						'validate_function'=>'genTags'	
						),
		 'desc'		=> array(
						'title'=> lang('vdo_desc'),
						'type'=> 'textarea',
						'name'=> 'group_description',
						'id'=> 'group_description',
						'value'=> cleanForm($gpdescription),
						'size'=>'35',
						'extra_params'=>' rows="4" ',
						'db_field'=>'group_description',
						'invalid_err'=>lang('grp_des_error'),
						'required'=>'yes',
						'max_length'=>config('grp_max_desc')
							 
						),
		 $url_form,
		 
		  'cat'		=> array(
						'title'=> lang('grp_cat_tile'),
						'type'=> 'checkbox',
						'name'=> 'category[]',
						'id'=> 'category',
						'value'=> array('category',$cat_array),
						'hint_1'=>  sprintf(lang('vdo_cat_msg'),ALLOWED_GROUP_CATEGORIES),
						'db_field'=>'category',
						'required'=>'yes',
						'validate_function'=>'validate_group_category',
						'invalid_err'=>lang('grp_cat_error'),
						'display_function' => 'convert_to_categories',
						'category_type'=>'group',
						),
		  
		 );
		
		return $fields;
	}
	
	
	/**
	 * Function used to load other group option fields
	 */
	function load_other_fields($default=NULL)
	{
		global $LANG,$uploadFormOptionFieldsArray;
		
		
		if(!$default)
			$default = $_POST;
			
		$gpprivacy = $default['group_privacy'];
		$gpposting = $default['post_type'];
		
		$group_option_fields = array
		(
		 'privacy'=> array('title'=>lang('privacy'),
							 'type'=>'radiobutton',
							 'name'=>'group_privacy',
							 'id'=>'group_privacy',
							 'value'=>array('0'=>lang('grp_join_opt1'),'1'=>lang('grp_join_opt2'),2=>lang('grp_join_opt3')),
							 'checked'=>$gpprivacy,
							 'db_field'=>'group_privacy',
							 'required'=>'no',
							 'display_function'=>'display_sharing_opt',
							 ),
		 'posting'=> array('title'=>lang('grp_forum_posting'),
							 'type'=>'radiobutton',
							 'name'=>'post_type',
							 'id'=>'post_type',
							 'value'=>array('0'=>lang('vdo_br_opt1'),'1'=>lang('vdo_br_opt2'),2=>lang('grp_join_opt3')),
							 'checked'=>$gpposting,
							 'db_field'=>'post_type',
							 'required'=>'no',
							 'display_function'=>'display_sharing_opt',
							 ),
		 );
		
		return $group_option_fields;
	}
	
	
	/**
	 * Function used to validate Signup Form
	 */
	function validate_form_fields($array=NULL,$update=false)
	{
		$fields = $this->load_required_fields($array,$update);

		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		//Mergin Array
		$group_fields = array_merge($fields,$this->load_other_fields());
		
		validate_cb_form($group_fields,$array);
		
	}
	
	/**
	 * Function used to create new groups
	 * @Author : Fawaz Tahir, Arslan Hassan
	 * @Params : array { Group Input Details }
	 * @since : 15 December 2009
	 */
	function create_group($array,$user=false,$redirect_to_group=false)
	{
		global $db;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
			
		$this->validate_form_fields($array);
		
		if(!error())
		{
			$group_fields = $this->load_required_fields($array);
			$group_fields = array_merge($group_fields,$this->load_other_fields());
			
			//Adding Custom Signup Fields
			if(count($this->custom_group_fields)>0)
				$group_fields = array_merge($group_fields,$this->custom_group_fields);
			foreach($group_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = ($val);
				else
					$val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
		}
		
		if(!error())
		{
			//UID
			$query_field[] = "userid";
			$query_val[] = $user;
			
			//DATE ADDED
			$query_field[] = "date_added";
			$query_val[] = now();
			
			$query_field[] = "total_members";
			$query_val[] = 1;
			
			//Inserting IN Database now
			$db->insert(tbl($this->gp_tbl),$query_field,$query_val);
			$insert_id = $db->insert_id();
			
			//Owner Joiing Group
			ignore_errors();
			
			$db->insert(tbl($this->gp_mem_tbl),
			array("group_id","userid","date_added","active"),
			array($insert_id,$user,now(),'yes'));


			//$this->join_group($insert_id,$user,false);
			
			//Updating User Total Groups
			$this->update_user_total_groups($user);
			
			//Adding Feed
			addFeed(array('action'=>'create_group','object_id' => $insert_id,'object'=>'group'));
			
			if($redirect_to_group)
			{
				$grp_details = $this->get_details($insert_id);
				redirect_to(group_link(array('details'=>$grp_details) ));
			}
			
			
			
			//loggin Upload
			$log_array = array
			(
			 'success'=>'yes',
			 'action_obj_id' => $insert_id,
			 'details'=> "created new group");
			insert_log('add_group',$log_array);
			
			return $insert_id;
		}
	}
	
	
	/**
	 * Function used to update group
	 * @Author : Fawaz Tahir, Arslan Hassan
	 * @Params : array { Group Input Details }
	 * @since : 15 December 2009
	 */
	function update_group($array=NULL)
	{
		global $db;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
			
		$this->validate_form_fields($array,true);
		
		$gid = $array['group_id'];
		
		if(!error())
		{
			$group_fields = $this->load_required_fields($array);
			$group_fields = array_merge($group_fields,$this->load_other_fields());
			
			//Adding Custom Signup Fields
			if(count($this->custom_group_fields)>0)
				$group_fields = array_merge($group_fields,$this->custom_group_fields);
			foreach($group_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = ($val);
				else
					$val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
		}
		
		
		if(has_access('admin_access',TRUE))
		{
			if(!empty($array['total_views']))
			{
				$query_field[] = 'total_views';
				$query_val[] = $array['total_views'];
			}
			if(!empty($array['total_videos']))
			{
				$query_field[] = 'total_videos';
				$query_val[] = $array['total_videos'];
			}
			if(!empty($array['total_members']))
			{
				$query_field[] = 'total_members';
				$query_val[] = $array['total_members'];
			}
			if(!empty($array['total_topics']))
			{
				$query_field[] = 'total_topics';
				$query_val[] = $array['total_topics'];
			}
		}
		
			//Getting Group URL value
			$gp_url = $this->get_gp_field_only($gid,"group_url");
			//Checking Group URL
			if($array['group_url']!=$gp_url)
				if(group_url_exists($array['group_url']))
					e(lang('grp_url_error2'));
		if(!error())
		{
			
			if(!userid())
			{
				e(lang("you_not_logged_in"));
			}elseif(!$this->group_exists($gid)){
				e(lang("grp_exist_error"));
			}elseif(!$this->is_owner($gid,userid()) && !has_access('admin_access',TRUE))
			{
				e(lang("you_cant_edit_group"));
			}else{
				
				$db->update(tbl($this->gp_tbl),$query_field,$query_val," group_id='$gid'");
				e(lang("grp_details_updated"),'m');
				
				//Updating Group Thumb
				if(!empty($array['thumb_file']['tmp_name']))
					$this->create_group_image($gid,$array['thumb_file']);
			}
		}
	}


	/**
	 * Function used to get default image for group.
	 */
	function get_default_thumb($size=NULL)
	{
		if($size=="small" && file_exists(TEMPLATEDIR.'/images/thumbs/group_thumb-small.png'))
		{
			return TEMPLATEURL.'/images/thumbs/group_thumb-small.png';
		}elseif(file_exists(TEMPLATEDIR.'/images/thumbs/group_thumb.png') && !$size)
		{
			return TEMPLATEURL.'/images/thumbs/group_thumb.png';
		}else
		{
			if($size=='small')
				$this->get_default_thumb = GP_THUMB_URL.'/no_thumb-small.png';
			else
				$this->get_default_thumb = GP_THUMB_URL.'/no_thumb.png';
			return $this->get_default_thumb;
		}
	}
	
	
	/**
	 * Function used to create group thumbnail
	 * @param = $gpid {ID of group for which thumbnail is being created }
	 * @param = $file { Source of image file $_FILES }
	 */
	function create_group_image($gpid,$file)
	{
		global $imgObj;
		$file_ext = strtolower(getext($file['name']));
		$exts = array('jpg','png','gif','jpeg');
		
		foreach($exts as $ext)
		{
			if($ext == $file_ext)
			{
				$thumb_name = $gpid.'.'.$ext;
				$small_thumb_name = $gpid.'-small.'.$ext;
				$path = GP_THUMB_DIR.'/'.$thumb_name;
				$small_path = GP_THUMB_DIR.'/'.$small_thumb_name;
				foreach($exts as $unlink_ext)
						if(file_exists(GP_THUMB_DIR.'/'.$gpid.'.'.$unlink_ext))
							unlink(GP_THUMB_DIR.'/'.$gpid.'.'.$unlink_ext);
							
				move_uploaded_file($file['tmp_name'],$path);
				
				if(!$imgObj->ValidateImage($path,$ext)) 
					e(lang('pic_upload_vali_err'));	
				else
				{
					$imgObj->CreateThumb($path,$path,$this->gp_thumb_width,$ext,$this->gp_thumb_height,true);
					$imgObj->CreateThumb($path,$small_path,$this->gp_small_thumb_width,$ext,$this->gp_small_thumb_height,true);
				}
			}
		}
	}
	
	/**
	 * Function used to get group thumb.
	 * @param Group Details array
	 */
	function get_gp_thumb($grp_details,$size=NULL)
	{
		$exts = array('jpg','png','gif','jpeg');
		$gpid = $grp_details['group_id'];
		foreach($exts as $ext)
		{
			if($size == 'small')
				$file_size = "-small";
				
			if(file_exists(GP_THUMB_DIR.'/'.$gpid."$file_size.".$ext))	
				return GP_THUMB_URL.'/'.$gpid."$file_size.".$ext;
		}
		
		return $this->get_default_thumb($size);

	}
	function get_group_thumb($grp_details,$size=NULL){ return $this->get_gp_thumb($grp_details,$size); }
	
	/**
	 * function used to get group icon
	 */
	function get_topic_icon($topic)
	{
		$file = TOPIC_ICON_DIR.'/'.$topic['topic_icon'];
		if(file_exists($file) && !empty($topic['topic_icon']))
		{
			return TOPIC_ICON_URL.'/'.$topic['topic_icon'];
		}else
			return TOPIC_ICON_URL.'/dot.gif';
	}


	/**
	 * Function used add new topic in group
	 * @param ARRAY details
	 */
	function add_topic($array,$redirect_to_topic=false)
	{
		global $db;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		
		$fields = $this->load_add_topic_form_fields($array);
		validate_cb_form($fields,$array);
		
		$user = userid();
		
		if(!error())
		{
			foreach($fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				
				if(!$field['clean_func'] || (!apply_func($field['clean_func'],$val) && !is_array($field['clean_func'])))
					$val = $val;
				else
					$val = apply_func($field['clean_func'],sql_free($val));
				
				if(empty($val) && !empty($field['default_value']))
					$val = $field['default_value'];
					
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
		}
		
		$gp_details = $this->get_group_details($array['group_id']);
		//Checking for weather user is allowed to post topics or not
		$this->validate_posting_previlige($gp_details);

		if(!error())
		{
			//Adding Topic icon
			$query_field[] = "topic_icon";
			$query_val[] = $array['topic_icon'];	
			//UID
			$query_field[] = "userid";
			$query_val[] = $user;
			//DATE ADDED
			$query_field[] = "date_added";
			$query_val[] = now();
			
			$query_field[] = "last_post_time";
			$query_val[] = now();
			
			//GID
			$query_field[] = "group_id";
			$query_val[] = $array['group_id'];
			
			//Checking If posting requires approval or not
			$query_field[] = "approved";
			if($gp_details['post_type']==1)
				$query_val[] = "no";
			else
				$query_val[] = "yes";

			//Inserting IN Database now
			$db->insert(tbl($this->gp_topic_tbl),$query_field,$query_val);
			$insert_id = $db->insert_id();
			
			//Increasing Group Topic Counts
			$count_topics = $this->count_group_topics($array['group_id']);
			$db->update(tbl($this->gp_tbl),array("total_topics"),array($count_topics)," group_id='".$array['group_id']."'");
			
			//leaving msg
			e(lang("grp_tpc_msg"),"m");
			
			//Redirecting to topic
			if($redirect_to_topic)
			{
				$grp_details = $this->get_details($insert_id);
				redirect_to(group_link($grp_details));
			}
			
			return $insert_id;
			
		}
	}
	
	/**
	 * Function used to delete group topic
	 */
	function delete_topic($tid)
	{
		global $db;
		$topic = $this->get_topic_details($tid);
		$group = $this->get_group_details($topic['group_id']);
		if(!$topic)
			e(lang("grp_tpc_err4"));
		elseif(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$topic['userid'] && userid()!=$group['userid'])
			e(lang("you_cant_del_topic"));
		else
		{
			$db->delete(tbl($this->gp_topic_tbl),array("topic_id","group_id"),array($tid,$topic['group_id']));
			//Deleting Topic Posts
			$this->delete_comments($tid);
			
			//Counting Total Topics
			$count_topics = $this->count_topics($topic['group_id']);
			
			//Updating Total Number
			$db->update(tbl($this->gp_tbl),array('total_topics'),array($count_topics)," group_id = '".$topic['group_id']."'");
			
			e(lang("grp_tpc_msg1"),"m"); 
		}
	}
	
	/**
	 * Function used to delete all user topics
	 */
	function delete_user_topics($uid,$gid)
	{
		global $db;
		$group = $this->get_group_details($gid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$group['userid'] && userid()!=$uid && !has_access('admin_access',true))
			e(lang("you_cant_del_user_topics"));
		else
		{
			$usr_topics = $this->get_topics(array('group'=>$gid,'user'=>$uid));
			if(is_array($usr_topics))
			foreach($usr_topics as $topic)
				$this->delete_topic($topic['topic_id']);
			e(lang("topics_deleted"),"m");
		}
	}
	
	
	/**
	 * Function used to delete all group topics
	 */
	function delete_group_topics($gid)
	{
		global $db;
		$group = $this->get_group_details($gid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$group['userid'] && !has_access('admin_access',true))
			e(lang("you_cant_delete_grp_topics"));
		else
		{
			$topics = $this->get_topics(array('group'=>$gid));
			if(is_array($topics))
			foreach($topics as $topic)
				$this->delete_topic($topic['topic_id']);
			e(lang("deleted"),"m");
		}
	}
	
	
	/**
	 * Function used to check weather posting is allowed or not
	 */
	function validate_posting_previlige($gdetails)
	{
		if(is_numeric($getails))
			$gdetails = $this->get_group_details($getails);
		
		if(!$gdetails || empty($gdetails['group_id']))
			e(lang("grp_exist_error"));
		if(!userid())
			e(lang("grp_please_login"));
		elseif(!$this->is_member(userid(),$gdetails['group_id'],TRUE))
			e(lang("you_not_grp_mem_or_approved"));
		elseif($gdetails['post_type']==2 && userid() != $gdetails['userid']  && !has_access('admin_access',true))
			e(lang("you_not_allowed_post_topics"));
		else
		{
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Function used to get group topics
	 * INPUT Group ID
	 */
	function get_group_topics($params)
	{
		global $db;
		
		$gid = $params['group'] ? $params['group'] : $params;
		$limit = $params['limit'];
		$order = $params['order'] ? $params['order'] : " last_post_time DESC ";
		
		if($params['approved'])
			$approved_query = " AND approved='yes' ";
		if($params['user'])
			$user_query = " AND userid='".$params['user']."'";
			
		$results = $db->select(tbl($this->gp_topic_tbl),"*"," group_id='$gid' $approved_query  $user_query",$limit,$order);
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	function GetTopics($params){ return $this->get_group_topics($params); }
	function get_topics($params){ return $this->get_group_topics($params); }

	/**
	 * Function used to check weather topic exists or not
	 * @param TOPIC ID {id of topic}
	 */
	function topic_exists($tid)
	{
		global $db;
		$count = $db->count(tbl($this->gp_topic_tbl),'topic_id'," topic_id='$tid' ");
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get topic details
	 * @param TOPIC ID {id of topic}
	 */	
	function get_topic_details($topic)
	{
		global $db;
		$result = $db->select(tbl($this->gp_topic_tbl),"*"," topic_id='$topic' ");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	function gettopic($topic){ return $this->get_topic_details($topic); }
	function get_topic($topic){ return $this->get_topic_details($topic); }

	/**
	 * Function used to check weather user is invited or not
	 */
	function is_invited($uid,$gid,$owner,$gen_err=FALSE)
	{
		global $db;
		$count = $db->count(tbl($this->gp_invite_tbl),'invitation_id'," invited='$uid' AND group_id='$gid' AND userid='$owner' ");
		if($count[0]>0)
			return true;
		else
		{
			if($gen_err)
				e(lang('grp_prvt_err1'));
			return false;
		}
	}
	function is_userinvite($uid,$gid,$owner){ return $this->is_invited($uid,$gid,$owner); }


	/**
	 * Function used to check whether user is already a member or not 
	 * @param = $user { User to check }
	 * @param = $gpid { ID of group in which we will check }
	 */
	function is_member($user,$gpid,$active=false) {
		global $db;
			
		$active_query = "";
		if($active)
			$active_query = " AND active='yes' ";
			
		$data = $db->count(tbl($this->gp_mem_tbl),"*","group_id='$gpid' AND userid='$user' $active_query");
		//echo $db->db_query;
		if($data[0]>0) {
			return true;	
		} else {
			return false;	
		}
	}
	function joined_group($user,$gpid){return $this->is_member($user,$gpid);}

	/**
	 * Function use to check weather user is owner or not of the group
	 * @param GID {group id}
	 * @param UID {logged in user or just user}
	 */
	function is_owner($gid,$uid=NULL)
	{
		if(!$uid)
			$uid = userid();
			
		if(!is_array($gid))
			$group = $this->get_group_details($gid);
		else
			$group = $gid;
		if($group['userid']==$uid)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Function used to count total number of members in a group.
	 * @param = $gpid { ID of group whose members are going to be counted }
	 */
	function total_members($gpid,$active=true)
	{
		global $db;
		if($active)
			$activeQuery = "AND active = 'yes'";
		$totalmem = $db->count(tbl("group_members"),"*","group_id='$gpid' $activeQuery");
		return $totalmem[0];
	}
	
			
	/**
	 * Function used to get group members
	 */
	function get_members($gid,$approved=NULL,$limit=NULL)
	{
		global $db;
		
		$app_query = "";
		if($approved)
			$app_query = " AND ".tbl($this->gp_mem_tbl).".active='$approved'"; 
		$result = $db->select(tbl($this->gp_mem_tbl)." LEFT JOIN ".tbl('users')." ON ".tbl($this->gp_mem_tbl).".userid=".tbl('users').".userid","*"," group_id='$gid' $app_query",$limit);

		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	
	/**
	 * Function used to check weather member is active or not
	 */
	function is_active_member($uid,$gid)
	{
		global $db;
		$count = $db->count(tbl($this->gp_mem_tbl),"userid"," userid='$uid' AND group_id='$gid' AND active='yes'");
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to count number of topics in a group
	 */
	function count_group_topics($group)
	{
		global $db;
		$totaltopics = $db->count(tbl($this->gp_topic_tbl),"*","group_id='$group'");
		return $totaltopics;
	}
	function CountTopics($group){ return $this->count_group_topics($group); }
	function count_topics($group){ return $this->count_group_topics($group); }


	
	/**
	 * Function used to add video to group
	 */
	function add_group_video($vid,$gid,$update_group=false)
	{
		global $db,$cbvid;
		$group = $this->get_details($gid);
		$video = $cbvid->get_video_details($vid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$video)
			e(lang("class_vdo_del_err"));
		elseif($video['userid']!=userid())
			e(lang("you_cant_add_this_vdo"));
		elseif($this->is_group_video($vid,$gid))
			return false;	
		else
		{
			if(!$this->is_active_member(userid(),$group['group_id']))
			{	$approved = "no"; e(lang("your_video_send_in_modetation"),"w"); }
			else
				$approved = "yes";
					
			$db->insert(tbl($this->gp_vdo_tbl),array("videoid","group_id","userid","approved"),array($vid,$gid,userid(),$approved));
			e(lang("video_added"),"m");
			if($update_group)
				$this->update_group_videos_count($gid);
		}
	}
	
	/**
	 * Removing video gro group
	 */
	function remove_group_video($vid,$gid,$update_group=false)
	{
		global $db,$cbvid;
		$group = $this->get_details($gid);
		$video = $cbvid->get_video_details($vid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$video)
			e(lang("class_vdo_del_err"));
		elseif($video['userid']!=userid())
			e(lang("you_cant_del_this_vdo"));
		elseif(!$this->is_group_video($vid,$gid))
			return false;
		else
		{
			$db->delete(tbl($this->gp_vdo_tbl),array("videoid","group_id"),array($vid,$gid));
			e(lang("video_removed"),"m");
			if($update_group)
				$this->update_group_videos_count($gid);
		}
	}



	/**
	 * Function used to check weather video is already in group or not
	 */
	function is_group_video($vid,$gid)
	{
		global $db;
		$count = $db->count(tbl($this->gp_vdo_tbl),"group_video_id"," videoid='$vid' AND group_id='$gid'");
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Function used to count videos of group
	 * @param GID {group ID}
	 */
	function count_videos($gpid,$approved=true)
	{
		global $db;
		if($approved)
			$appQuery = "AND approved = 'yes'";	
		$totalmem = $db->count(tbl("group_videos"),"*","group_id='$gpid'  $appQuery");
		return $totalmem;
	}
	function total_videos($gid){return $this->count_videos($gid);}
	
	
	
	/**
	 * Function used to update number of videos of group
	 */
	function update_group_videos_count($gid)
	{
		global $db;
		$total = $this->count_videos($gid);
		$db->update(tbl($this->gp_tbl),array('total_videos'),array($total)," group_id='$gid'");
	}
	
	
	/**
	 * Function used to get group videos
	 */
	function get_group_videos($gid,$approved=NULL,$limit=NULL)
	{
		global $db,$cbvid;
		if($approved)
			$approved_query = "AND approved='$approved'";
		
		
		if($limit!='count')
		{
			$result = $db->select(tbl($this->gp_vdo_tbl)." LEFT JOIN ".tbl('video')." 
			ON ".tbl($this->gp_vdo_tbl).".videoid=".tbl('video').".videoid","*",
			" group_id='$gid' $approved_query AND ".tbl('video').".active='yes' AND status='Successful'",$limit);
		}else
		{
			return $db->count(tbl($this->gp_vdo_tbl)." LEFT JOIN ".tbl('video')." 
			ON ".tbl($this->gp_vdo_tbl).".videoid=".tbl('video').".videoid","videoid",
			" group_id='$gid' $approved_query AND ".tbl('video').".active='yes' AND status='Successful'",$limit);
		}

		if($db->num_rows>0)
			return $result;
		else
			return false;
			
	}
	

	/**
	 * Function used to activate or detactivate  or delete group member
	 */
	function member_actions($gid,$memuid,$case,$deleting_group=FALSE)
	{
		global $db;
		
		//getting group details
		$group = $this->get_group_details($gid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$this->is_member($memuid,$gid))
			e(lang("user_not_grp_mem"));
		elseif(userid()!=$group['userid'] && !has_access("admin_access",true))
			e(lang("grp_owner_err1"));
		elseif($group['userid']==$memuid && !$deleting_group)
			e(lang("you_cant_perform_actions_on_grp_own"));
		else
		switch($case)
		{
			case "activate":
			case "active":
			{
				$db->update(tbl($this->gp_mem_tbl),array("active"),array("yes"),"userid='$memuid' AND group_id='$gid'");
				$total_members = $this->total_members($group['group_id']);
				$db->update(tbl($this->gp_tbl),array("total_members"),array($total_members)," group_id='".$gid."'");
				e(lang("usr_ac_msg"),"m");
			}
			break;
			
			case "deactivate":
			case "deactive":
			case "unactivate":
			case "unactive":
			{
				$db->update(tbl($this->gp_mem_tbl),array("active"),array("no"),"userid='$memuid' AND group_id='$gid'");
				$total_members = $this->total_members($group['group_id']);
				$db->update(tbl($this->gp_tbl),array("total_members"),array($total_members)," group_id='".$gid."'");
				e(lang("usr_dac_msg"),"m");
			}
			break;
			
			case "delete":
			{
				
				//Delete All Videos oF member
				$db->delete(tbl($this->gp_vdo_tbl),array("userid","group_id"),array($memuid,gid));
				//Deleting ALl Topics Of 
				$this->delete_user_topics($memuid,$gid);
				//Delete Member
				$db->delete(tbl($this->gp_mem_tbl),array("userid","group_id"),array($memuid,$gid));
				
				$total_members = $this->total_members($gid);
				$total_videos = $this->total_videos($gid);
				$count_topics = $this->count_group_topics($gid);
				
				//Update Stat
				$db->update(tbl($this->gp_tbl),array("total_topics","total_members","total_videos"),
												array($count_topics,$total_members,$total_videos)," group_id='".$gid."'");

				e(lang("usr_del_msg"),"m");
			}
			break;
		}
			
	}
	
	
	
	/**
	 * Function used to perform actions on videos
	 */
	function video_actions($gid,$vid,$case)
	{
		global $db;
		
		//getting group details
		$group = $this->get_group_details($gid);


		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$this->is_group_video($vid,$gid))
			e(lang("class_vdo_del_err"));
		elseif(userid()!=$group['userid'] && !has_access("admin_access"))
			e(lang("grp_owner_err1"));
		else
		switch($case)
		{
			case "activate":
			case "active":
			{
				$db->update(tbl($this->gp_vdo_tbl),array("approved"),array("yes"),"videoid ='$vid' AND group_id='$gid'");
				$this->update_group_videos_count($gid);
				e(lang("class_vdo_act_msg"),"m");
			}
			break;
			
			case "deactivate":
			case "deactive":
			case "unactivate":
			case "unactive":
			{
				$db->update(tbl($this->gp_vdo_tbl),array("approved"),array("no"),"videoid ='$vid' AND group_id='$gid'");
				$this->update_group_videos_count($gid);
				e(lang("class_vdo_act_msg1"),"m");
			}
			break;
			
			case "delete":
			{
				//Delete video
				$db->delete(tbl($this->gp_vdo_tbl),array("videoid","group_id"),array($vid,$gid));	
				
				$total_videos = $this->total_videos($gid);
				//Update Stat
				$db->update(tbl($this->gp_tbl),array("total_videos"),
												array($total_videos)," group_id='".$gid."'");
				e(lang("class_vdo_del_msg"),"m");
			}
			break;
		}
		
	}
		
	/**
	 * Function used to invite members to group
	 */
	function invite_member($user,$gid,$owner=NULL)
	{
		global $cbemail,$db,$userquery;
		$group = $this->get_group_details($gid);
		
		if(!$owner)
			$owner = userid();
		
		$sender = $userquery->get_user_details($owner);
		$reciever = $userquery->get_user_details($user);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(!$sender)
			e(lang("unknown_sender"));
		elseif(!$reciever)
			e(lang("unknown_reciever"));
		elseif($this->is_member($user,$gid))
			e(lang("user_already_group_mem"));
		elseif($owner != $group['userid'])
			e(lang("grp_owner_err1"));
		else
		{
			//Inserting Invitation Code in database
			$db->insert(tbl($this->gp_invite_tbl),array('group_id','userid','invited','date_added'),
												   array($gid,$owner,$reciever['userid'],now()));
			e(lang("grp_inv_msg"),"m");
			
			//Now Sending Email To User
			$tpl = $cbemail->get_template('group_invitation');
			
			$more_var = array
			(
			 '{reciever}'	=> $reciever['username'],
			 '{sender}'		=> $sender['username'],
			 '{group_url}'	=> group_link(array('details'=>$group)),
			 '{group_name}'	=> $group['group_name'],
			 '{group_description}'	=> $group['group_description']
			 
			);
			
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			//Now Finally Sending Email
			cbmail(array('to'=>$reciever['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));		
		}
	}
		
	/**
	 * Function used to invite members to group
	 */
	function invite_members($user_array,$group,$owner=NULL)
	{
		global $eh;
		$total = count($user_array);
		for($i=0;$i<$total;$i++)
		{
			$this->invite_member($user_array[$i],$group,$owner);
		}
		$eh->flush();
		e(lang("invitations_sent"),"m");
	}


	/**
	 * Function used to leave group
	 */
	function leave_group($gid,$uid)
	{
		global $db;
		if(!$this->is_member($uid,$gid))
			e(lang("you_not_grp_mem"));
		elseif($this->is_owner($gid,$uid))
			e(lang("grp_owner_err2"));
		else
		{
			$db->delete(tbl($this->gp_mem_tbl),array("userid","group_id"),array($uid,$gid));
			e(lang("grp_leave_succ_msg"),"m");
		}
	}


	/**
	 * Function used to delete group
	 */
	function delete_group($gid)
	{
		global $db;
		$group = $this->get_group_details($gid);
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$group['userid'] && !has_access('admin_access',true))
			e(lang("you_cant_delete_this_grp"));
		else
		{
			//Deleting Everything Related To This Group
			$this->delete_group_topics($gid);
			$this->delete_group_videos($gid);
			$this->delete_group_members($gid);
			$db->delete(tbl($this->gp_tbl),array("group_id"),array($gid));
			$this->update_user_total_groups($group['userid']);
			e(lang("grp_deleted"),"m");
		}
	}
	
	/**
	 * Functin used to delete all memebrs of group
	 */
	function delete_group_members($gid)
	{
		global $db;
		$group = $this->get_group_details($gid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$group['userid'] && !has_access('admin_access',true))
			e(lang("you_cant_del_grp_mems"));
		else
		{
			$db->delete(tbl($this->gp_mem_tbl),array("group_id"),array($gid));
			e(lang("mems_deleted"),"m");
		}
	}
	
	/**
	 * Functin used to delete all videos of group
	 */
	function delete_group_videos($gid)
	{
		global $db;
		$group = $this->get_group_details($gid);
		
		if(!$group)
			e(lang("grp_exist_error"));
		elseif(userid()!=$group['userid'] && !has_access('admin_access',true))
			e(lang("you_cant_del_grp_vdos"));
		else
		{
			$db->delete(tbl($this->gp_vdo_tbl),array("group_id"),array($gid));
			e(lang("vdo_multi_del_erro"),"m");
		}
	}


	/**
	 * Function used to check weather group exists or not
	 */
	function group_exists($gid)
	{
		global $db;
		$result = $db->count(tbl($this->gp_tbl),"group_id"," group_id='$gid'");
		if($result>0)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Function used to perform gorups actions
	 */
	function grp_actions($type,$gid)
	{
		global $db;
		$gdetails = $this->get_details($gid);
		if(!$gdetails)
			e(lang("grp_exist_error"));
		else
		{
			switch($type)
			{
				case "activate":
				case "active":
				{
					$db->update(tbl($this->gp_tbl),array("active"),array("yes")," group_id='$gid' ");
					e(lang("grp_av_msg"),"m");
				}
				break;
				case "deactivate":
				case "deactive":
				{
					$db->update(tbl($this->gp_tbl),array("active"),array("no")," group_id='$gid' ");
					e(lang("grp_da_msg"),"m");
				}
				break;
				case "featured":
				case "feature":
				{
					$db->update(tbl($this->gp_tbl),array("featured"),array("yes")," group_id='$gid' ");
					e(lang("grp_fr_msg"),"m");
				}
				break;
				case "unfeatured":
				case "unfeature":
				{
					$db->update(tbl($this->gp_tbl),array("featured"),array("no")," group_id='$gid' ");
					e(lang("grp_fr_msg2"),"m");
				}
				break;
				case "delete":
				{
					$this->delete_group($gid);
					e(lang("grp_del_msg"),"m");
				}
				break;
			}
		}
	}
	
	/**
	 * Function used to get all topic icons
	 */
	function get_topic_icons()
	{
		$dir = TOPIC_ICON_DIR;
		$icons = glob($dir.'/*.png');
		$new_icons = '';
		foreach($icons as $icon)
		{
			$icon_parts = explode('/',$icon);
			$icon_file = $icon_parts[count($icon_parts)-1];
			$new_icons[] = array('file'=>$icon_file,'path'=>$icon,'url'=>TOPIC_ICON_URL.'/'.$icon_file);
		}
		
		if(count($new_icons)>0)
			return $new_icons;
		else
			return false;
	}
	
	
	

	
	/**
	 * Function used to load ADD Topic Form
	 */
	function load_add_topic_form_fields($array=NULL)
	{
		if($array==NULL)
			$array = $_POST;
		
		$topic_title = $_POST['topic_title'];
		$topic_post = $_POST['topic_post'];

		$fields = array
		(
		'title'	=> array(	
						 'title'=> lang('topic_title'),
						 'type'=> 'textfield',
						 'name'=> 'topic_title',
						 'id'=> 'topic_title',
						 'value'=>  cleanForm($topic_title),
						 'size'=>'45',
						 'db_field'=>'topic_title',
						 'required'=>'yes',
						 'min_length' => 1,
						 'max_length'=>config('max_topic_title'),
						 
						 ),
		'topic_post'	=> array(	
						 'title'=> lang("topic_post"),
						 'type'=> 'textarea',
						 'name'=> 'topic_post',
						 'id'=> 'topic_post',
						 'value'=>  cleanForm($topic_post),
						 'size'=>'45',
						 'db_field'=>'topic_post',
						 'required'=>'yes',
						 'min_length' => 4,
						 'max_length'=>config('max_topic_length'),
						 'anchor_before' => 'before_topic_post_box',
						 'anchor_after' => 'after_topic_post_box',
						 )								 
		);
		return $fields;
	}
	
	
	
	/**
	 * Function used to add video comment
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL)
	{
		global $myquery,$db;
		
		if(!$this->topic_exists($obj_id))
			e(lang("grp_tpc_err4"));
		else
		{
			$owner = $this->get_group_owner_from_topic($obj_id);
			$add_comment =  $myquery->add_comment($comment,$obj_id,$reply_to,'t',$owner);
			if($add_comment)
			{
				//Loggin Comment			
				$log_array = array
				(
				 'success'=>'yes',
				 'details'=> "comment on a topic",
				 'action_obj_id' => $obj_id,
				 'action_done_id' => $add_comment,
				);
				insert_log('topic_comment',$log_array);
				
				//Updating Number of comments of topics
				$this->update_comments_count($obj_id);
			}
			return $add_comment;
		}
	}
	
	/**
	 * Function used to delete comment
	 */
	function delete_comment($cid,$objid)
	{
		global $myquery;
		$tdetails = $this->get_topic_details($objid);
		$gdetails = $this->get_group_details($tdetails['group_id']);
		
		$forceDelete = false;
		if(userid()==$gdetails['userid'])
			$forceDelete = true;
		$myquery->delete_comment($cid,'t',false,$forceDelete);
		$this->update_comments_count($objid);	
	}
	
	/**
	 * Function delete all comments of topic
	 */
	function delete_comments($tid)
	{
		global $myquery;
		$tdetails = $this->get_topic_details($tid);
		$gdetails = $this->get_group_details($tdetails['group_id']);
		$forceDelete = false;
		if(userid()==$gdetails['userid'])
		{
			$forceDelete = true;		
			$myquery->delete_comments($tid,'t',$forceDelete);
			$this->update_comments_count($objid);
		}
	}
	
	/**
	 * Function used to update video comments count
	 */
	function update_comments_count($id)
	{
		global $db;
		$total_comments = $this->count_topic_comments($id);
		if(!userid())
			$userid = 0;
		else
			$userid = userid();
			
		$db->update(tbl($this->gp_topic_tbl),array("total_replies","last_poster","last_post_time"),
										array($total_comments,$userid,now())," topic_id='$id'");
	}
	
	/**
	 * Function used to count total video comments
	 */
	function count_topic_comments($id)
	{
		global $db;
		$total_comments = $db->count(tbl('comments'),"comment_id","type='t' AND type_id='$id'");
		return $total_comments;
	}
	
	/**
	 * Function used to crearte view topic link
	 */
	function topic_link($tdetails)
	{
		if(SEO==yes)
			return BASEURL.'/view_topic/'.SEO($tdetails['topic_title']).'_tid_'.$tdetails['topic_id'];
		else
			return BASEURL.'/view_topic.php?tid='.$tdetails['topic_id'];
	}
	
	
	/**
	 * function and show otpion links
	 */
	function group_opt_link($group,$type)
	{
		global $userquery;
		$gArray = 
		array
		(
			'group' => $group,
			'groupid'	=> $group['group_id'],
			'uid'	=> userid(),
			'user'	=> $userquery->udetails,
			'checkowner' => 'yes'
		);
		switch($type)
		{
			case 'join':
			{
				if($this->is_joinable($group))
				{
					if(SEO=="yes")
						return '<a href="'.group_link(array('details'=>$group)).'?join=yes">'.lang('join').'</a>';
					else
						return '<a href="'.group_link(array('details'=>$group)).'&join=yes">'.lang('join').'</a>';
				}else
					return false;
			}
			break;
			
			case 'invite':
			{
				if($this->is_owner($group))
				{
					return '<a href="'.BASEURL.'/invite_group.php?url='.$group['group_url'].'">'.lang('invite').'</a>';
				}
			}
			break;
			
			case 'leave':
			{
				if($this->is_member(userid(),$group['group_id']) && !$this->is_owner($group))
				{
					if(SEO=="yes")
						return '<a href="'.group_link(array('details'=>$group)).'?leave=yes">'.lang('leave').'</a>';
					else
						return '<a href="'.group_link(array('details'=>$group)).'&leave=yes">'.lang('leave').'</a>';
				}
			}
			break;
			
			case 'remove_group':
			{
				if($this->is_owner($group))
				{
					$gpID = $group['group_id']; $message = lang('are_you_sure_del_grp');
					$url = BASEURL.'/manage_groups.php?mode=manage&gid_delete='.$gpID;
					return "<a href='javascript:void(0)' id='delete_group-".$gpID."' onmousedown='delete_item(\"delete_group\",\"$gpID\",\"$message\",\"$url\")'>".lang('grp_remove_group')."</a>";
				}
			}
			break;
			
			case 'manage_members':
			{
				
				if($this->is_admin($gArray))
				{
					return '<a href="'.BASEURL.'/manage_groups.php?mode=manage_members&gid='.$group['group_id'].'">'.lang('grp_manage_mems').'</a>';
				}
			}
			break;
			
			case 'manage_videos':
			{
				if($this->is_admin($gArray))
				{
					return '<a href="'.BASEURL.'/manage_groups.php?mode=manage_videos&gid='.$group['group_id'].'">'.lang('com_manage_vids').'</a>';
				}
			}
			break;
			
			
			case 'add_videos':
			{
				if($this->is_member(userid(),$group['group_id']))
				{
					return '<a href="'.BASEURL.'/add_group_videos.php?url='.$group['group_url'].'">'.lang('grp_add_vdos').'</a>';
				}
			}
			break;
			
			case 'edit_group':
			{
				if($this->is_admin($gArray))
				{
					return '<a href="'.BASEURL.'/edit_group.php?gid='.$group['group_id'].'">'.lang('grp_edit_grp_title').'</a>';
				}
			}
			break;
		}
		return false;
	}
	
	/**
	 * Function used to check weather
	 * this group is joinable or not
	 * it will check
	 * - user is logged in or not
	 * - if user is logged in , check is he member or not
	 * - if he is not a member, check is he invited
	 * - if is invited then show the link
	 */
	function is_joinable($group,$uid=NULL,$gen_err=FALSE)
	{
		if(!$uid)
			$uid = userid();
			
		$group_id = $group['group_id'];
		if($this->is_member($uid,$group['group_id']))
		{
			if($gen_err)
			e(lang('grp_join_error'));
			return false;
		}elseif($group['group_privacy']!=2 || $this->is_invited($uid,$group_id,$group['userid'],$gen_err))
			return true;
		else
			return false;	
	}
	
	
	/**
	 * Function used to check weather to view
	 * group details ot user or not.
	 * We need to check following things and display Message Accordingly
	 * First - we to save group privacy into variables
	 * Second - $privacy == 1 and user is not member of group. Display group but with warning message.
	 * Third - $privacy == 1 and user is member of group. Display group but with warning message about pending approval.
	 * Fourth - $privacy == 2 and user is not member. Dont display group with error message
	 */
	function is_viewable($group,$uid=NULL)
	{
		if(!$uid)
			$uid = userid();
		$privacy = $group['group_privacy'];
		$isMember = $this->is_member($uid,$group['group_id']);
		
		if($privacy == 1 && !$isMember)
		{
			e(lang("you_need_owners_approval_to_view_group"),"w");
			return true;	
		} elseif($privacy == 1 && !$this->is_active_member($uid,$group['group_id']))
		{
				e(lang("grp_inactive_account"),"w");
				return true;
		} elseif($privacy == 2 && !$isMember && !$this->is_invited($uid,$group['group_id'],$group['userid'])) {
			e(lang("grp_prvt_err1"));
			return false;	
		} else {
			return true;	
		}
		
/*		$group_id = $group['group_id'];
		$is_Member = $this->is_member($uid,$group['group_id'],true);
		
		if($group['group_privacy'] == 2 && !$is_Member)
			return array("privacy"=>$group['group_privacy'],"isMember"=>$is_Member);
		elseif($group['group_privacy'] == 1 && !$is_Member)
			return array("privacy"=>$group['group_privacy'],"isMember"=>$is_Member);
		else
			return true;	*/
	}
	
	
	
	/**
	 * Function used to get groups
	 * @parma ARRAY
	 * group_id => {id of group} INT
	 * user => {all groups of the user INT
	 * category => {all groups in specified category INT,INT,INT
	 * featured => {get featured groups only} yes,no
	 * limit => {number of results including offset}
	 * order => {soring by}
	 * date_margin => {date span}
	 */
	function get_groups($params=NULL,$force_admin=FALSE)
	{
		global $db;
		
		$limit = $params['limit'];
		$order = $params['order'];
		
		$cond = "";
		if(!has_access('admin_access',TRUE) && !$force_admin)
			$cond .= " ".tbl("groups.active")."='yes' ";
		else
		{
			if($params['active'])
				$cond .= " ".tbl("groups.active")."='".$params['active']."'";
		}
		
		//Setting Category Condition
		if(!is_array($params['category']))
			$is_all = strtolower($params['category']);
			
		if($params['category'] && $is_all!='all')
		{
			if($cond!='')
				$cond .= ' AND ';
				
			$cond .= " (";
			
			if(!is_array($params['category']))
			{
				$cats = explode(',',$params['category']);
			}else
				$cats = $params['category'];
				
			$count = 0;
			
			foreach($cats as $cat_params)
			{
				$count ++;
				if($count>1)
				$cond .=" OR ";
				$cond .= " ".tbl("groups.category")." LIKE '%#$cat_params#%' ";
			}
			
			$cond .= ")";
		}
		
		//date span
		if($params['date_span'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".cbsearch::date_margin("date_added",$params['date_span']);
		}
		
		//uid 
		if($params['user'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".tbl("groups.userid")."='".$params['user']."'";
		}
		
		$tag_n_title='';
		//Tags
		if($params['tags'])
		{
			//checking for commas ;)
			$tags = explode(",",$params['tags']);
			if(count($tags)>0)
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$total = count($tags);
				$loop = 1;
				foreach($tags as $tag)
				{
					$tag_n_title .= " ".tbl("groups.group_tags")." LIKE '%".$tag."%'";
					if($loop<$total)
					$tag_n_title .= " OR ";
					$loop++;
					
				}
			}else
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$tag_n_title .= " ".tbl("groups.group_tags")." LIKE '%".$params['tags']."%'";
			}
		}
		//TITLE
		if($params['title'])
		{
			if($tag_n_title!='')
				$tag_n_title .= ' OR ';
			$tag_n_title .= " ".tbl("groups.group_name")."  LIKE '%".$params['title']."%'";
		}
		
		if($tag_n_title)
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ($tag_n_title) ";
		}
		
		//FEATURED
		if($params['featured'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".tbl("groups.featured")." = '".$params['featured']."' ";
		}
		
		//GROUP ID
		if($params['group_id'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " group_id = '".$params['group_id']."' ";
		}
		
		//Exclude Vids
		if($params['exclude'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".tbl("groups.group_id")." <> '".$params['exclude']."' ";
		}
		
		
		
		if(!$params['count_only'])
		{
			if(!empty($cond))
			$cond .= " AND ";
			$result = $db->select(tbl($this->gp_tbl.",users"),''.tbl($this->gp_tbl).'.*, '.tbl("users").'.username, '.tbl("users").'.userid',$cond." ".tbl("groups.userid")." = ".tbl("users.userid")." ",$limit,$order);
		}
		
		// echo $db->db_query;
		if($params['count_only'])
			return $result = $db->count(tbl($this->gp_tbl),'*',$cond);
		if($params['assign'])
			assign($params['assign'],$result);
		else
			return $result;
	}
	
	
	
	
	
	/**
	 * Function used to get group field
	 * @ param INT gid 
	 * @ param FIELD name
	 */
	function get_gp_field($gid,$field)
	{
		global $db;
		$results = $db->select(tbl($this->gp_tbl),$field,"group_id='$gid'");
		
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}function get_gp_fields($gid,$field){return $this->get_gp_field($gid,$field);}
	
	
	/**
	 * This function will return
	 * group field without array
	 */
	function get_gp_field_only($gid,$field)
	{
		$fields = $this->get_gp_field($gid,$field);
		return $fields[$field];
	}
	
	/**
	 * Function used to get groups joined by user
	 */
	function user_joined_groups($uid,$limit=NULL)
	{
		global $db;
		# REF QUERY : SELECT * FROM group_members,groups WHERE group_members.userid = '1' AND group_members.group_id = groups.group_id AND groups_members.userid != groups.userid
		$result = $db->select(tbl($this->gp_tbl).','.tbl($this->gp_mem_tbl),"*",tbl($this->gp_mem_tbl).".userid='$uid' AND 
							  ".tbl($this->gp_mem_tbl).".group_id = ".tbl($this->gp_tbl).".group_id AND ".tbl($this->gp_mem_tbl).".userid != ".tbl($this->gp_tbl).".userid",$limit,tbl($this->gp_tbl).".group_name");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	
	/***
	 * Function used to update user total number of groups
	 */
	function update_user_total_groups($user)
	{
		global $db;
		$count = $db->count(tbl($this->gp_tbl),"group_id"," userid='$user' ");
		$db->update(tbl("users"),array("total_groups"),array($count)," userid='$user' ");
	}
	
	
	
	
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "groups";
		$this->search->columns =array(
			array('field'=>'group_name','type'=>'LIKE','var'=>'%{KEY}%'),
		);
		$this->search->cat_tbl = $this->cat_tbl;

		$this->search->display_template = LAYOUT.'/blocks/group.html';
		$this->search->template_var = 'group';
		$this->search->multi_cat = true;
		$this->search->results_per_page = config('grps_items_search_page');
		$this->search->has_user_id = true;
		
		/**
		 * Setting up the sorting thing
		 */
		
		$sorting	= 	array(
						'date_added'	=> lang("date_added"),
						'total_views'		=> lang("views"),
						'total_comments'  => lang("comments"),
						'total_videos' 	=> lang("videos"),
						'total_members' 	=> lang("total members"),
						);
		
		$this->search->sorting	= array(
						'date_added'=> " date_added DESC",
						'total_views'		=> " total_views DESC",
						'total_comments'  => " total_comments DESC ",
						'total_videos' 	=> " total_videos DESC",
						'total_members' 	=> " total_members DESC",
						);
		/**
		 * Setting Up The Search Fields
		 */
		 
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['groups'] = array('title'=>'Groups');
		
		$fields = array(
		'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
						),
		'category'	=>  array(
						'title'		=> lang('category'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						'category_type'=>'group',
						),
		'date_margin'	=>  array(
						'title'		=> lang('created'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
						),
		'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
							)
		);

		$this->search->search_type['groups']['fields'] = $fields;
	}
	
	
	/**
	 * Function used to validate group category
	 * @param input array
	 */
	function validate_group_category($array=NULL)
	{
		if($array==NULL)
			$array = $_POST['category'];
		if(count($array)==0)
			return false;
		else
		{
			
			foreach($array as $arr)
			{
				if($this->category_exists($arr))
					$new_array[] = $arr;
			}
		}
		if(count($new_array)==0)
		{
			e(lang('vdo_cat_err3'));
			return false;
		}elseif(count($new_array)>ALLOWED_GROUP_CATEGORIES)
		{
			e(sprintf(lang('vdo_cat_err2'),ALLOWED_GROUP_CATEGORIES));
			return false;
		}
			
		return true;
	}
	
	
	/**
	 * Get group owner from topic
	 */
	function get_group_owner_from_topic($tid)
	{
		global $db;
		$results = $db->select(tbl("group_topics").",".tbl("groups"),
			tbl("group_topics").".group_id,".tbl("group_topics").".topic_id,".tbl("groups")."userid,".tbl("groups").".group_id",
			tbl("group_topics").".group_id = ".tbl("groups").".group_id AND ".tbl("group_topics").".topic_id='$tid'");

		if($db->num_rows>0)
			return $results[0]['userid'];
		else
			return false;
	}
	
	/**
	 * Function used to make member admin of the group
	 * input ARRAY
	 * INDEX gid => groupid
	 * INDEX group => groupdetails
	 * INDEX uid => Userid
	 * INDEX user => userdtails
	 * return error() | return true on success makeAdmin
	 */
	function make_admin($array){ return $this->makeAdmin($array);}
	function makeAdmin($array)
	{
		global $userquery,$db;
		extract($array);
		if(!@$groupid)
			e(lang('Unknown group'));
		elseif(!@$group)
		{
			$group = $this->get_group($groupid);
		}
		
		if(!@$uid)
			e(lang('Unknown group user'));
		elseif(!@$user)
		{
			$user = $userquery->get_user_details($uid);
		}
		
		if(!$group)
			e(lang("Unknown group"));
		if(!$user)
			e(lang("Unknown user"));		
		
		//if(!$this->is_member($uid,$groupid))
		//	e(sprintf(lang("%s is not a member of %s"),$user['username'],$group['group_name']));
		if(!$this->is_active_member($uid,$groupid))
			e(sprintf(lang("%s is not active member of %s"),$user['username'],$group['group_name']));
				
			
		//Checking if is owner or already an admin
		$this->is_admin(array(
		'group'=>$group,
			'groupid'=>$groupid,
				'uid'=>$uid,
					'user'=>$user,
						'error'=>true,
							'checkowner'=>true));
		
		if(!error())
		{
			$groupAdmins = $group['group_admins'];
			$groupAdmins = json_decode($groupAdmins,true);
			$groupAdmins[] = $uid;
			$groupAdmins = json_encode($groupAdmins);
			
			$db->update(tbl("groups"),array("group_admins"),
			array('|no_mc|'.$groupAdmins)," group_id='".$groupid."'");
			e(sprintf(lang("%s has been made adminstrator of %s"),$user['username'],$group['group_name']),"m");
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Function used to get weather user is admin of the group or not
	 * input ARRAY
	 * INDEX gid => groupid
	 * INDEX group => groupdetails
	 * INDEX uid => Userid
	 * INDEX user => userdtails
	 * return error() | return true on success makeAdmin
	 */
	function is_admin($array)
	{
		global $userquery;
		extract($array);
		if(!@$groupid)
			e(lang('Unknown group'));
		elseif(!@$group)
		{
			$group = $this->get_group($groupid);
		}
		
		if(!@$uid)
			e(lang('Unknown group user'));
		elseif(!@$user)
		{
			$user = $userquery->get_user_details($uid);
		}
		if(!$group)
			e(lang("Unknown group"));
		if(!$user)
			e(lang("Unknown user"));
		
		//Moving group admins into an array
		$groupAdmins = $group['group_admins'];
		$groupAdmins = json_decode($groupAdmins,true);
		
		if($group['userid']== $uid && $checkowner)
		{
			if(@$error)
			e(sprintf(lang('%s is owner of %s'),$user['username'],$group['group_name']));
			return true;
		}elseif(@in_array($uid,$groupAdmins))
		{
			if(@$error)
			e(sprintf(lang('%s is admin of %s'),$user['username'],$group['group_name']));
			return true;
		}
		
		return false;		
	}
	
	
	/**
	 * Removing admin from group
	 */
	function remove_admin($array){ return $this->removeAdmin($array);}
	function removeAdmin($array)
	{
		global $userquery,$db;
		extract($array);
		if(!@$groupid)
			e(lang('Unknown group'));
		elseif(!@$group)
		{
			$group = $this->get_group($groupid);
		}
		
		if(!@$uid)
			e(lang('Unknown group user'));
		elseif(!@$user)
		{
			$user = $userquery->get_user_details($uid);
		}
		
		if(!$group)
			e(lang("Unknown group"));
		if(!$uid)
			e(lang("Unknown user"));
			
				
		//Checking if is owner or already an admin
		if(!$this->is_admin(array(
			'group'=>$group,
			'groupid'=>$groupid,
			'uid'=>$uid,
			'user'=>$user)))
		{
			e(sprintf(lang('%s is not admin of %s'),$user['username'],$group['group_name']));
			return false;
		}else
		{
			$groupAdmins = $group['group_admins'];
			$groupAdmins = json_decode($groupAdmins,true);
			$newAdmins = array();
			foreach($groupAdmins as $gadmin)
				if($gadmin!=$uid)
					$newAdmins[] = $gadmin;
			
			$groupAdmins = json_encode($newAdmins);
			$db->update(tbl("groups"),array("group_admins"),
			array('|no_mc|'.$groupAdmins)," group_id='".$groupid."'");
			e(sprintf(lang("%s has been removed from adminstrators of %s"),$user['username'],$group['group_name']),"m");
			return true;
			
		}			
			
	}
	
	
}

function isGroupAdmin($array){ global $cbgroup; $return = $cbgroup->is_admin($array); 
if($array['assign']) assign($array['assign'],$return); else return $return;}
function removeGroupAdmin($array){ global $cbgroup; $return = $cbgroup->removeAdmin($array); 
if($array['assign']) assign($array['assign'],$return); else return $return;}
function makeGroupAdmin($array){ global $cbgroup; $return = $cbgroup->make_admin($array);
if($array['assign']) assign($array['assign'],$return); else return $return; }

?>