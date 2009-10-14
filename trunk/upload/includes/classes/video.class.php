<?php
/**
 * Author : Arslan Hassan
 * Script : ClipBucket v2
 * LIcense : CBLA
 *
 *
 * Class : Video
 * Used to perform function swith videos
 * -- history
 * all function that were in my_query
 * has been transfered here
 * however thhey will still work from there
 * too
 */
 
 

class CBvideo extends CBCategory
{
	var $embed_func_list = array(); //Function list that are applied while asking for video embed code
	var $action = ''; // variable used to call action class
	var $email_template_vars = array();
	
	/**
	 * __Constructor of CBVideo
	 */	
	function CBvideo()
	{
		$this->cat_tbl = 'video_categories';
		$this->section_tbl = 'video';
		$this->init_actions();
	}
	
	
	/**
	 * Function used to check weather video exists or not
	 * @param VID or VKEY
	 */
	function video_exists($vid)
	{
		return $this->get_video($vid);
	}
	function exists($vid){return $this->video_exists($vid);}
	function videoexists($vid){return $this->video_exists($vid);}
	
	
	/**
	 * Function used to get video data
	 */
	function get_video($vid)
	{
		global $db;
		$results = $db->select("video","*"," videoid='$vid' OR videokey='$vid'");
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	function getvideo($vid){return $this->get_video($vid);}
	function get_video_data($vid){return $this->get_video($vid);}
	function getvideodata($vid){return $this->get_video($vid);}
	function get_video_details($vid){return $this->get_video($vid);}
	function getvideodetails($vid){return $this->get_video($vid);}
	
	
	/**
	 * Function used to perform several actions with a video
	 */
	function action($case,$vid)
	{
		global $db;
		if(!$this->exists($vid))
			return false;
		//Lets just check weathter video exists or not
		switch($case)
		{
			//Activating a video
			case 'activate':
			case 'av':
			case 'a':
			{
				$db->update("video",array('active'),array('yes')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg"),m);
			}
			break;
			
			//Deactivating a video
			case "deactivate":
			case "dav":
			case "d":
			{
				$db->update("video",array('active'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg1"),m);
			}
			break;
			
			//Featuring Video
			case "feature":
			case "featured":
			case "f":
			{
				$db->update("video",array('featured'),array('yes')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_fr_msg"),m);
			}
			break;
			
			
			//Unfeatured video
			case "unfeature":
			case "unfeatured":
			case "uf":
			{
				$db->update("video",array('featured'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_fr_msg1"),m);
			}
			break;
		}
	}
	
	
	
	/**
	 * Function used to update video
	 */
	function update_video()
	{
		global $eh,$Cbucket,$db,$Upload;

		$Upload->validate_video_upload_form(NULL,TRUE);
		
		if(empty($eh->error_list))
		{
			$required_fields = $Upload->loadRequiredFields($array);
			$location_fields = $Upload->loadLocationFields($array);
			$option_fields = $Upload->loadOptionFields($array);
			
			$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
			
			//Adding Custom Upload Fields
			if(count($Upload->custom_upload_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_upload_fields);
			//Adding Custom Form Fields
			if(count($Upload->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_form_fields);
			
			$array = $_POST;
			$vid = $array['videoid'];

			if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		
			foreach($upload_fields as $field)
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
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],$val);
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}		
			
			#$query = "INSERT INTO video (";
			$total_fields = count($query_field);
			
			//Adding Fields to query
			$i = 0;
			
			/*for($key=0;$key<$total_fields;$key++)
			{
				$query .= query_field[$key]." = '".$query_val[$key]."'" ;
				if($key<$total_fields-1)
				$query .= ',';
			}*/
			
			if(has_access('admin_access'))
			{
				$query_field[] = 'status';
				$query_field[] = 'duration';
				$query_val[] = $array['status'];
				$query_val[] = $array['duration'];
			}
			
			if(!userid())
			{
				e("You are not logged in");
			}elseif(!$this->video_exists($vid)){
				e("Video deos not exist");
			}else{
				$db->update('video',$query_field,$query_val," videoid='$vid'");
				e("Video details have been updated",m);
			}
			
		}
	}
	
	
	/**
	 * Function used to delete a video
	 */
	function delete_video($vid)
	{
		global $db;
		
		if($this->video_exists($vid))
		{
			$vdetails = $this->get_video($vid);
			//list of functions to perform while deleting a video
			$del_vid_funcs = $this->video_delete_functions;
			if(is_array($del_vid_funcs))
			{
				foreach($del_vid_funcs as $func)
				{
					if(function_exists($func))
					{
						$func($vdetails);
					}
				}
			}
			//Finally Removing Database entry of video
			$db->execute("DELETE FROM video WHERE videoid='$vid'");
			e(lang("class_vdo_del_msg"),m);
		}else{
			e(lang("class_vdo_del_err"));
		}
		
	}
	
	/**
	 * Function used to remove video thumbs
	 */
	function remove_thumbs($vdetails)
	{
		//First lets get list of all thumbs
		$thumbs = get_thumb($vdetails,1,true,false,false);
		if(!is_default_thumb($thumbs))
		{
			if(is_array($thumbs))
			{
				foreach($thumbs as $thumb)
				{
					$file = THUMBS_DIR.'/'.$thumb;
					if(file_exists($file) && is_file($file))
						unlink($file);
				}
			}else{
				$file = THUMBS_DIR.'/'.$thumbs;
					if(file_exists($file) && is_file($file))
						unlink($file);
			}
			
			e(lang("vid_thumb_removed_msg"),m);
		}
	}
	
	
	
	/**
	 * Function used to remove video log
	 */
	function remove_log($vdetails)
	{
		global $db;
		$src = $vdetails['videoid'];
		$db->execute("DELETE FROM video_file WHERE src_name = '$src'");
		e(lang("vid_log_delete_msg"),m);
	}
	
	/**
	 * Function used to remove video files
	 */
	function remove_files($vdetails)
	{
		//Getting list of files
		$files = get_video_file($vdetails,false,false,true);
		if(is_array($files))
		{
			foreach($files as $file)
			{
				if(file_exists(VIDEOS_DIR.'/'.$file) && is_file(VIDEOS_DIR.'/'.$file))
					unlink(VIDEOS_DIR.'/'.$file);
			}
		}else{
			if(file_exists(VIDEOS_DIR.'/'.$files) && is_file(VIDEOS_DIR.'/'.$files))
					unlink(VIDEOS_DIR.'/'.$files);
		}
		e(lang("vid_files_removed_msg"),m);
	}
	
	
	/**
	 * Function used to get videos
	 * this function has all options
	 * that you need to fetch videos
	 * please see docs.clip-bucket.com for more details
	 */
	function get_videos($params)
	{
		$cond_array = array();
		
		//Setting Category Condition
		if($params['category'])
		{
			if(is_array($params['category']))
			{
				foreach($params['category'] as $cat_params)
				{
					$cond_array[] = " category ='$cat_params' ";
				}
			}else{
				$cond_array[] = " category ='".$params['category']."' ";
			}
		}
		
		//date span
		switch($param['date_span'])
		{
			case 'today';
			{
				
			}
		}
		
	}
	
	
	/**
	 * Function used to add video comment
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL)
	{
		global $myquery;
		return $myquery->add_comment($comment,$obj_id,$reply_to,'v');
	}
	
	
	/**
	 * Function used to generate Embed Code
	 */
	function embed_code($vdetails)
	{
		//Checking for video details
		if(!is_array($vdetails))
		{
			$vdetails = $this->get_video($vdetails);
		}
		
		$embed_code = false;
		
		$funcs = $this->embed_func_listl;
		if(is_array($funcs))
		{
			foreach($funcs as $func)
			{
				if(@function_exists($func))
				$embed_code = $func($vdetails);
			}
		}
		
		if(!$embed_code)
		{
			//Default ClipBucket Embed Code
			if(function_exists('default_embed_code'))
			{
				$embed_code = default_embed_code($vdetails);
			}
		}
		
		return $embed_code;
		
	}
	
	
	/**
	 * Function used to initialize action class
	 * in order to call actions.class.php to
	 * work with Video section, this function will be called first
	 */
	function init_actions()
	{
		$this->action = new cbactions();
		$this->action->type = 'v';
		$this->action->name = 'video';
		$this->action->obj_class = 'cbvideo';
		$this->action->check_func = 'video_exists';
	}
	
	
		
	/**
	 * Function used to create value array for email templates
	 * @param video_details ARRAY
	 */
	function set_share_email($details)
	{
		$this->email_template_vars = array
		('{video_title}' => $details['title'],
		 '{video_description}' => $details['tags'],
		 '{video_tags}' => $details['description'],
		 '{video_date}' => cbdate(DATE_FORMAT,strtotime($details['date_added'])),
		 '{video_link}' => video_link($details),
		 '{video_thumb}'=> GetThumb($details)
		 );
		
		$this->action->share_template_name = 'share_video_template';
		$this->action->val_array = $this->email_template_vars;
	}
	
	
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "video";
		$this->search->columns =array(
			array('field'=>'title','type'=>'LIKE','var'=>'%{KEY}%'),
			array('field'=>'tags','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR')
		);
		$this->search->cat_tbl = $this->cat_tbl;
		
		/**
		 * Setting up the sorting thing
		 */
		
		$sorting	= 	array(
						'date_added'=> lang("date_added"),
						'views'		=> lang("views"),
						'comments'  => lang("comments"),
						'rating' 	=> lang("rating"),
						'favorites'	=> lang("favorites")
						);
		
		$this->search->sorting	= array(
						'date_added'=> " date_added DESC",
						'views'		=> " views DESC",
						'comments'  => " comments_count DESC ",
						'rating' 	=> " rating DESC",
						'favorites'	=> " favorites DeSC"
						);
		/**
		 * Setting Up The Search Fields
		 */
		 
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['video'] = array('title'=>'Video');
		$fields = array(
		'keyword'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'keywords',
						'id'=> 'keywords',
						'value'=>cleanForm($default['keywords'])
						),
		'category'	=>  array(
						'title'		=> lang('vdo_cat'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						),
		'uploaded'	=>  array(
						'title'		=> lang('uploaded'),
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

		$this->search->search_type['video']['fields'] = $fields;
	}
	
}
?>