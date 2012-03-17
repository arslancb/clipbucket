<?php


	/** 
	 * Function used to call display
	 */
	function display_it()
	{
		global $ClipBucket;
		$dir = LAYOUT;
		foreach($ClipBucket->template_files as $file)
		{
			if(file_exists(LAYOUT.'/'.$file) || is_array($file))
			{
				
				if(!$ClipBucket->show_page && $file['follow_show_page'])
				{
					
				}else
				{
					if(!is_array($file))
						$new_list[] = $file;
					else
					{
						if($file['folder'] && file_exists($file['folder'].'/'.$file['file']))
							$new_list[] = $file['folder'].'/'.$file['file'];
						else
							$new_list[] = $file['file'];
					}
				}							
			}
		}
		
		assign('template_files',$new_list);

		Template('body.html');
		
		footer();
	}
	
	
	/**
	 * Function used to display hint
	 */
	function hint($hint)
	{
		
	}
	
	
	
	function showpagination($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		global $pages;
		return $pages->pagination($total,$page,$link,$extra_params,$tag);
	}
	
	
	/**
	 * Function used to check username is disallowed or not
	 * @param USERNAME
	 */
	function check_disallowed_user($username)
	{
		global $Cbucket;
		$disallowed_user = $Cbucket->configs['disallowed_usernames'];
		$censor_users = explode(',',$disallowed_user);
		if(in_array($username,$censor_users))
			return false;
		else
			return true;
	}

	
	
	
	
	
	
	
	
	
	/**
	 * Function used to check weather email already exists or not
	 * @input email
	 */
	function email_exists($user)
	{
		global $userquery;
		return $userquery->duplicate_email($user);
	}
	
	/**
	 * function used to check weather group URL exists or not
	 */
	function group_url_exists($url)
	{
		global $cbgroup;
		return $cbgroup->group_url_exists($url);
	}
	
	
	/**
	 * Function used to check weather erro exists or not
	 */
	function error($param='array')
	{
		if(count(error_list())>0)
		{
			if($param!='array')
			{
				if($param=='single')
					$param = 0;
				$msg = error_list();
				return $msg[$param];
			}
			return error_list();
		}else{
			return false;
		}
	}
	
	/**
	 * Function used to check weather msg exists or not
	 */
	function msg($param='array')
	{
		if(count(msg_list())>0)
		{
			if($param!='array')
			{
				if($param=='single')
					$param = 0;
				$msg = msg_list();
				return $msg[$param];
			}
			return msg_list();
		}else{
			return false;
		}
	}
	
	
	
	/**
	 * Function used to load plugin
	 * please check docs.clip-bucket.com
	 */
	function load_plugin()
	{
		global $cbplugin;
		
	}
	
	
	
	/**
	 * Function used to create limit functoin from current page & results
	 */
	function create_query_limit($page,$result)
	{
		$limit  = $result;	
		if(empty($page) || $page == 0 || !is_numeric($page)){
		$page   = 1;

		}
		$from 	= $page-1;
		$from 	= $from*$limit;
		
		return $from.','.$result;
	}
	
	
	/**
	 * Function used to get value from $_GET
	 */
	function get_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_GET[$val]);
		else
			return $_GET[$val];
	}function get($val){ return get_form_val($val); }
	
	/**
	 * Function used to get value form $_POST
	 */
	function post_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_POST[$val]);
		else
			$_POST[$val];
	}
	
	
	/**
	 * Function used to get value from $_REQUEST
	 */
	function request_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_REQUEST[$val]);
		else
			$_REQUEST[$val];
	}
	
	
	/**
	 * Function used to return LANG variable
	 */
	function lang($var,$sprintf=false)
	{
		global $LANG,$Cbucket;

		$array_str = array
		( '{title}');
		$array_replace = array
		( $Cbucket->configs['site_title'] );
		
		if($LANG[$var])
		{
			$phrase =  str_replace($array_str,$array_replace,$LANG[$var]);
		}else
		{
			$phrase = str_replace($array_str,$array_replace,$var);
		}
		
		if($sprintf)
		{
			$sprints = explode(',',$sprintf);
			if(is_array($sprints))
			{
				foreach($sprints as $sprint)
				{
					$phrase = sprintf($phrase,$sprint);
				}
			}
		}
		
		return $phrase;
		
	}
	function smarty_lang($param)
	{
		if($param['assign']=='')
			return lang($param['code'],$param['sprintf']);
		else
			assign($param['assign'],lang($param['code'],$param['sprintf']));
	}
	
	
	
	/**
	 * function used to remove video thumbs
	 */
	function remove_video_thumbs($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_thumbs($vdetails);
	}
	
	/**
	 * function used to remove video log
	 */
	function remove_video_log($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_log($vdetails);
	}
	
	/**
	 * function used to remove video files
	 */
	function remove_video_files($vdetails)
	{
		global $cbvid;
		return $cbvid->remove_files($vdetails);
	}
	
	
	/**
	 * Function used to get player logo
	 */
	function website_logo()
	{
		$logo_file = config('player_logo_file');
		if(file_exists(BASEDIR.'/images/'.$logo_file) && $logo_file)
			return BASEURL.'/images/'.$logo_file;
		
		return BASEURL.'/images/logo.png';
	}
	
	/**
	 * Function used to assign link
	 */
	function cblink($params)
	{
		global $ClipBucket;
		$name = $params['name'];
		$ref = $param['ref'];
		
		if($name=='category')
		{
			return category_link($params['data'],$params['type']);
		}
		if($name=='sort')
		{
			return sort_link($params['sort'],'sort',$params['type']);
		}
		if($name=='time')
		{
			return sort_link($params['sort'],'time',$params['type']);
		}
		if($name=='tag')
		{
			return BASEURL.'/search_result.php?query='.urlencode($params['tag']).'&type='.$params['type'];
		}
		if($name=='category_search')
		{
			return BASEURL.'/search_result.php?category[]='.$params['category'].'&type='.$params['type'];
		}
		
		
		if(SEO!='yes')
		{
			preg_match('/http:\/\//',$ClipBucket->links[$name][0],$matches);
			if($matches)
				$link = $ClipBucket->links[$name][0];
			else
				$link = BASEURL.'/'.$ClipBucket->links[$name][0];
		}else
		{
			preg_match('/http:\/\//',$ClipBucket->links[$name][1],$matches);
			if($matches)
				$link = $ClipBucket->links[$name][1];
			else
				$link = BASEURL.'/'.$ClipBucket->links[$name][1];
		}
		
		$param_link = "";
		if(!empty($params['extra_params']))
		{
			preg_match('/\?/',$link,$matches);
			if(!empty($matches[0]))
			{
				$param_link = '&'.$params['extra_params'];
			}else{
				$param_link = '?'.$params['extra_params'];
			}
		}
		
		if($params['assign'])
			assign($params['assign'],$link.$param_link);
		else
			return $link.$param_link;
	}
	
	/**
	 * Function used to check video is playlable or not
	 * @param vkey,vid
	 */
	function video_playable($id)
	{
		global $cbvideo,$userquery;
		
		if(isset($_POST['watch_protected_video']))
			$video_password = mysql_clean(post('video_password'));
		else
			$video_password = '';
		
		if(!is_array($id))
		$vdo = $cbvideo->get_video($id);
		else
		$vdo = $id;
		$uid = userid();
		if(!$vdo)
		{
			e(lang("class_vdo_del_err"));
			return false;
		}elseif($vdo['status']!='Successful')
		{
			e(lang("this_vdo_not_working"));
			if(!has_access('admin_access',TRUE))
				return false;
			else
				return true;
		}elseif($vdo['broadcast']=='private' 
				&& !$userquery->is_confirmed_friend($vdo['userid'],userid()) 
				&& !is_video_user($vdo)
				&& !has_access('video_moderation',true) 
				&& $vdo['userid']!=$uid){
			e(lang('private_video_error'));
			return false;
		}elseif($vdo['active'] == 'pen'){
				e(lang("video_in_pending_list"));
				if(has_access('admin_access',TRUE) || $vdo['userid'] == userid())
					return true;
				else
					return false;
		}elseif($vdo['broadcast']=='logged' 
				&& !userid()
				&& !has_access('video_moderation',true) 
				&& $vdo['userid']!=$uid){
			e(lang('not_logged_video_error'));
			return false;
		}elseif($vdo['active']=='no' )
		{
			e(lang("vdo_iac_msg"));
			if(!has_access('admin_access',TRUE))
				return false;
			else
				return true;
		}
		//No Checking for video password
		elseif($vdo['video_password'] 
			&& $vdo['broadcast']=='unlisted'
			&& $vdo['video_password']!=$video_password
			&& !has_access('video_moderation',true) 
			&& $vdo['userid']!=$uid)
		{
			if(!$video_password)
			e(lang("video_pass_protected"));
			else
			e(lang("invalid_video_password"));
			template_files("blocks/watch_video/video_password.html",false,false);
		}
		else
		{
			$funcs = cb_get_functions('watch_video');
			
			if($funcs)
			foreach($funcs as $func)
			{
				$data = $func['func']($vdo);
				if($data)
					return $data;
			}
			return true;
		}
	}
	
	
	/**
	 * Function used to show rating
	 * @inputs
	 * class : class used to show rating usually rating_stars
	 * rating : rating of video or something
	 * ratings : number of rating
	 * total : total rating or out of
	 */
	function show_rating($params)
	{
		$class 	= $params['class'] ? $params['class'] : 'rating_stars';
		$rating 	= $params['rating'];
		$ratings 	= $params['ratings'];
		$total 		= $params['total'];
		$style		= $params['style'];
		if(empty($style))
			$style = config('rating_style');
		//Checking Percent

		{
			if($total<=10)
				$total = 10;
			$perc = $rating*100/$total;
			$disperc = 100 - $perc;		
			if($ratings <= 0 && $disperc == 100)
				$disperc = 0;
		}
				
		$perc = $perc.'%';
		$disperc = $disperc."%";		
		switch($style)
		{
			case "percentage": case "percent":
			case "perc": default:
			{
				$likeClass = "UserLiked";
				if(str_replace('%','',$perc) < '50')
					$likeClass = 'UserDisliked';
					
				$ratingTemplate = '<div class="'.$class.'">
									<div class="ratingContainer">
										<span class="ratingText">'.$perc.'</span>';
				if($ratings > 0)
					$ratingTemplate .= ' <span class="'.$likeClass.'">&nbsp;</span>';										
				$ratingTemplate .='</div>
								</div>';	
			}
			break;
			
			case "bars": case "Bars": case "bar":
			{
				$ratingTemplate = '<div class="'.$class.'">
					<div class="ratingContainer">
						<div class="LikeBar" style="width:'.$perc.'"></div>
						<div class="DislikeBar" style="width:'.$disperc.'"></div>
					</div>
				</div>';
			}
			break;
			
			case "numerical": case "numbers":
			case "number": case "num":
			{
				$likes = round($ratings*$perc/100);
				$dislikes = $ratings - $likes;
				
				$ratingTemplate = '<div class="'.$class.'">
					<div class="ratingContainer">
						<div class="ratingText">
							<span class="LikeText">'.$likes.' Likes</span>
							<span class="DislikeText">'.$dislikes.' Dislikes</span>
						</div>
					</div>
				</div>';
			}
			break;
			
			case "custom": case "own_style":
			{
				$file = LAYOUT."/".$params['file'];
				if(!empty($params['file']) && file_exists($file))
				{
					// File exists, lets start assign things
					assign("perc",$perc); assign("disperc",$disperc);
					
					// Likes and Dislikes
					$likes = floor($ratings*$perc/100);
					$dislikes = $ratings - $likes;
					assign("likes",$likes);	assign("dislikes",$dislikes);
					Template($file,FALSE);										
				} else {
					$params['style'] = "percent";
					return show_rating($params);	
				}
			}
			break;
		}
		/*$rating = '<div class="'.$class.'">
					<div class="stars_blank">
						<div class="stars_filled" style="width:'.$perc.'">&nbsp;</div>
						<div class="clear"></div>
					</div>
				  </div>';*/
		return $ratingTemplate;
	}
	

	/**
	 * Function used to display
	 * Blank Screen
	 * if there is nothing to play or to show
	 * then show a blank screen
	 */
	function blank_screen($data)
	{
		global $swfobj;
		$code = '<div class="blank_screen" align="center">No Player or Video File Found - Unable to Play Any Video</div>';
		$swfobj->EmbedCode(unhtmlentities($code),$data['player_div']);
		return $swfobj->code;
	}
	
	
	
	
	/**
	 * Function used to display an ad
	 */
	function ad($in)
	{
		return stripslashes(htmlspecialchars_decode($in));
	}
	
	
	/**
	 * Function used to get
	 * available function list
	 * for special place , read docs.clip-bucket.com
	 */
	function get_functions($name)
	{
		global $Cbucket;
		$funcs = $Cbucket->$name;
		if(is_array($funcs) && count($funcs)>0)
			return $funcs;
		else
			return false;
	}
	
	
	/**
	 * Function used to add js in ClipBuckets JSArray
	 * see docs.clip-bucket.com
	 */
	function add_js($files)
	{
		global $Cbucket;
		return $Cbucket->addJS($files);
	}
	
	/**
	 * Function add_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * for specific page array('page'=>'file') 
	 * ie array('uploadactive'=>'datepicker.js')
	 */
	function add_header($files)
	{
		global $Cbucket;
		return $Cbucket->add_header($files);
	}
	function add_admin_header($files)
	{
		global $Cbucket;
		return $Cbucket->add_admin_header($files);
	}
	
	
	/**
	 * Function used to get config value
	 * of ClipBucket
	 */
	function config($input)
	{
		global $Cbucket;
		return $Cbucket->configs[$input];
	}
	function get_config($input){ return config($input); }

	/**
	 * Funcion used to call functions
	 * when video is going to watched
	 * ie in watch_video.php
	 */
	function call_watch_video_function($vdo)
	{
		global $userquery;

		$funcs = get_functions('watch_video_functions');

		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}

		increment_views($vdo['videoid'],'video');

		if(userid())
			$userquery->increment_watched_vides(userid());

	}
	
	/**
	 * Funcion used to call functions
	 * when video is going
	 * on CBvideo::remove_files
	 */
	function call_delete_video_function($vdo)
	{
		$funcs = get_functions('on_delete_video');
		if(is_array($funcs) && count($funcs) > 0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}
	}

	
	/**
	 * Funcion used to call functions
	 * when video is going to dwnload
	 * ie in download.php
	 */
	function call_download_video_function($vdo)
	{		
		global $db;
		$funcs = get_functions('download_video_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}
		
		//Updating Video Downloads
		$db->update(tbl("video"),array("downloads"),array("|f|downloads+1"),"videoid = '".$vdo['videoid']."'");
		//Updating User Download
		if(userid())
		$db->update(tbl("users"),array("total_downloads"),array("|f|total_downloads+1"),"userid = '".userid()."'");
	}
	
	
	/**
	 * Funcion used to call functions
	 * when user view channel
	 * ie in view_channel.php
	 */
	function call_view_channel_functions($u)
	{
		$funcs = get_functions('view_channel_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($u);
				}
			}
		}
		
		increment_views($u['userid'],"channel");
	}
	
	
	
	
	/**
	 * Funcion used to call functions
	 * when user view topic
	 * ie in view_topic.php
	 */
	function call_view_topic_functions($tdetails)
	{
		$funcs = get_functions('view_topic_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($tdetails);
				}
			}
		}
		
		increment_views($tdetails['topic_id'],"topic");
	}


	

	/**
	 * Funcion used to call functions
	 * when user view group
	 * ie in view_group.php
	 */
	function call_view_group_functions($gdetails)
	{
		$funcs = get_functions('view_group_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($gdetails);
				}
			}
		}
		increment_views($gdetails['group_id'],"group");
	}
	
	/**
	 * Funcion used to call functions
	 * when user view collection
	 * ie in view_collection.php
	 */
	function call_view_collection_functions($cdetails)
	{
		$funcs = get_functions('view_collection_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($cdetails);
				}
			}
		};

		increment_views($cdetails['collection_id'],"collection");
	}

	
	/**
	 * Function used to incream number of view
	 * in object
	 */
	function increment_views($id,$type=NULL)
	{
		global $db;
		switch($type)
		{
			case 'v':
			case 'video':
			default:
			{
				if(!isset($_COOKIE['video_'.$id])){
					$db->update(tbl("video"),array("views","last_viewed"),array("|f|views+1",NOW())," videoid='$id' OR videokey='$id'");
					setcookie('video_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 'u':
			case 'user':
			case 'channel':

			{
				
				if(!isset($_COOKIE['user_'.$id])){
					$db->update(tbl("users"),array("profile_hits"),array("|f|profile_hits+1")," userid='$id'");
					setcookie('user_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 't':
			case 'topic':

			{
				if(!isset($_COOKIE['topic_'.$id])){
					$db->update(tbl("group_topics"),array("total_views"),array("|f|total_views+1")," topic_id='$id'");
					setcookie('topic_'.$id,'watched',time()+3600);
				}
			}
			break;
			break;
			case 'g':
			case 'group':

			{
				if(!isset($_COOKIE['group_'.$id])){
					$db->update(tbl("groups"),array("total_views"),array("|f|total_views+1")," group_id='$id'");
					setcookie('group_'.$id,'watched',time()+3600);
				}
			}
			break;
			case "c":
			case "collect":
			case "collection":
			{
				if(!isset($_COOKIE['collection_'.$id])){
					$db->update(tbl("collections"),array("views"),array("|f|views+1")," collection_id = '$id'");
					setcookie('collection_'.$id,'viewed',time()+3600);
				}
			}
			break;
			
			case "photos":
			case "photo":
			case "p":
			{
				if(!isset($_COOKIE['photo_'.$id]))
				{
					$db->update(tbl('photos'),array("views","last_viewed"),array("|f|views+1",NOW())," photo_id = '$id'");
					setcookie('photo_'.$id,'viewed',time()+3600);
				}
			}
		}
		
	}
	
	
	/**
	 * Function used to get post var
	 */
	function post($var)
	{
		return $_POST[$var];
	}
	
	
	/**
	 * Function used to show sharing form
	 */
	function show_share_form($array)
	{
		
		assign('params',$array);
		Template('blocks/share_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_flag_form($array)
	{
		assign('params',$array);
		Template('blocks/flag_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_playlist_form($array)
	{
		global $cbvid;
		assign('params',$array);
		
		$playlists = $cbvid->action->get_playlists();
		assign('playlists',$playlists);
		
		Template('blocks/playlist_form.html');
	}
	
	/**
	 * Function used to show collection form
	 */
	function show_collection_form($params)
	{
		global $db,$cbcollection;
		if(!userid())
			$loggedIn = "not";
		else	
		{		
			$collectArray = array("order"=>" collection_name ASC","type"=>"videos","user"=>userid());		
			$collections = $cbcollection->get_collections($collectArray);
			
			assign("collections",$collections);
		}
		Template("/blocks/collection_form.html");	
	}
	
	
	function cbdate($format=NULL,$timestamp=NULL)
	{
		if(!$format)
		{
			$format = config("datE_format");
		}
		if(!$timestamp)
			return date($format);
		else
			return date($format,$timestamp);
	}
	
	
	/**
	 * Function used to count pages
	 * @param TOTAL RESULTS NUM
	 * @param NUMBER OF RESULTS to DISPLAY NUM
	 */
	function count_pages($total,$count)
	{
		if($count<1) $count = 1;
		$records = $total/$count;
		return $total_pages = round($records+0.49,0);
	}
	
	
	
	
	
	/**
	 * This function used to check
	 * weather user is online or not
	 * @param : last_active time
	 * @param : time margin
	 */
	function is_online($time,$margin='5')
	{
		$margin = $margin*60;
		$active = strtotime($time);
		$curr = time();
		$diff = $curr - $active;
		if($diff > $margin)
			return 'offline';
		else
			return 'online';
	}
	
	
	/**
	 * ClipBucket Form Validator
	 * this function controls the whole logic of how to operate input
	 * validate it, generate proper error
	 */
	function validate_cb_form($input,$array)
	{
		
		if(is_array($input))
		foreach($input as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			if(is_string($val))
			{
				if(!isUTF8($val))
					$val = utf8_decode($val);
				$length = strlen($val);
			}
			$min_len = $field['min_length'];
			$min_len = $min_len ? $min_len : 0;
			$max_len = $field['max_length'] ;
			$rel_val = $array[$field['relative_to']];
			
			if(empty($invalid_err))
				$invalid_err =  sprintf("Invalid '%s'",$title);
			if(is_array($array[$field['name']]))
				$invalid_err = '';
				
			//Checking if its required or not
			if($req == 'yes')
			{
				if(empty($val) && !is_array($array[$field['name']]))
				{
					e($invalid_err);
					$block = true;
				}else{
					$block = false;
				}
			}
			$funct_err = is_valid_value($field['validate_function'],$val);
			if($block!=true)
			{
				
				//Checking Syntax
				if(!$funct_err)
				{
					if(!empty($function_error_msg))
						e($function_error_msg);
					elseif(!empty($invalid_err))
						e($invalid_err);
				}
				
				if(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				if(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(lang('please_enter_val_bw_min_max'),
							  $title,$min_len,$field['max_length']));
				}
				if(function_exists($field['db_value_check_func']))
				{

					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}
				if($field['relative_type']!='')
				{
					switch($field['relative_type'])
					{
						case 'exact':
						{
							if($rel_val != $val)
							{
								if(!empty($field['relative_err']))
									e($field['relative_err']);
								elseif(!empty($invalid_err))
									e($invalid_err);
							}
						}
						break;
					}
				}
			}	
		}
	}


	/**
	 * Function used to check weather tempalte file exists or not
	 * input path to file
	 */
	function template_file_exists($file,$dir)
	{
		if(!file_exists($dir.'/'.$file) && !empty($file) && !file_exists($file))
		{
			echo sprintf(lang("temp_file_load_err"),$file,$dir);
			return false;
		}else
			return true;
	}
	
	/** 
	 * Function used to count age from date
	 */
	function get_age($input)
	{ 
		$time = strtotime($input);
		$iMonth = date("m",$time);
		$iDay = date("d",$time);
		$iYear = date("Y",$time);
		
		$iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $iMonth, $iDay, $iYear); 
		$iDays = $iTimeStamp / 86400;  
		$iYears = floor($iDays / 365 );  
		return $iYears; 
	}
	
	
	
	
	/**
	 * Function used to check time span
	 * A time difference function that outputs the 
	 * time passed in facebook's style: 1 day ago, 
	 * or 4 months ago. I took andrew dot
	 * macrobert at gmail dot com function 
	 * and tweaked it a bit. On a strict enviroment 
	 * it was throwing errors, plus I needed it to 
	 * calculate the difference in time between 
	 * a past date and a future date. 
	 * thanks to yasmary at gmail dot com
	 */
	function nicetime($date,$istime=false)
	{
		if(empty($date)) {
			return lang('no_date_provided');
		}
	   
		$periods         = array(lang("second"), lang("minute"), lang("hour"), lang("day"), lang("week"), lang("month"), lang("year"), lang("decade"));
		$lengths         = array(lang("60"),lang("60"),lang("24"),lang("7"),lang("4.35"),lang("12"),lang("10"));
	   
		$now             = time();
		
		if(!$istime)
		$unix_date         = strtotime($date);
	    else
	   $unix_date         = $date;
	   
		   // check validity of date
		if(empty($unix_date)  || $unix_date<1) {   
			return lang("bad_date");
		}
	
		// is it future date or past date
		if($now > $unix_date) {   
			//time_ago
			$difference     = $now - $unix_date;
			$tense         = "time_ago";
		   
		} else {
			//from_now
			$difference     = $unix_date - $now;
			$tense         = "from_now";
		}
	   
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
	   
		$difference = round($difference);
	   
		if($difference != 1) {
			$periods[$j].= "s";
		}
	   	
		
		return sprintf(lang($tense),$difference,$periods[$j]);
	}
	
	
	/**
	 * Function used to format outgoing link
	 */
	function outgoing_link($out)
	{
		preg_match("/http/",$out,$matches);
		if(empty($matches[0]))
			$out = "http://".$out;
		return '<a href="'.$out.'" target="_blank">'.$out.'</a>';
	}
	
	/**
	 * Function used to get country via country code
	 */
	function get_country($code)
	{
		global $db;
		$result = $db->select(tbl("countries"),"name_en,iso2"," iso2='$code' OR iso3='$code'");
		if($db->num_rows>0)
		{
			$flag = '';
			$result = $result[0];
			if(SHOW_COUNTRY_FLAG)
				$flag = '<img src="'.BASEURL.'/images/icons/country/'.strtolower($result['iso2']).'.png" alt="" border="0">&nbsp;';
			return $flag.$result['name_en'];
		}else
			return false;
	}
	
	
	/**
	 * function used to get vidos
	 */
	function get_videos($param)
	{
		global $cbvideo;
		return $cbvideo->get_videos($param);
	}
	
	/**
	 * function used to get photos
	 */
	function get_photos($param)
	{
		global $cbphoto;
		return $cbphoto->get_photos($param);
	}
	
	/**
	 * function used to get photos
	 */
	function get_collections($param)
	{
		global $cbcollection;
		return $cbcollection->get_collections($param);
	}
	
	
	
	
	
	/**
	 * function used to get groups
	 */
	function get_groups($param)
	{
		global $cbgroup;
		return $cbgroup->get_groups($param);
	}
	
	/**
	 * Function used to call functions
	 */
	function call_functions($in,$params=NULL)
	{
		if(is_array($in))
		{
			foreach($in as $i)
			{
				if(function_exists($i))
					if(!$params)
						$i();
					else
						$i($params);
			}
		}else
		{
			if(function_exists($in))
					if(!$params)
						$in();
					else
						$in($params);
		}
		
	}
	

	
	
	/**
	 * In each plugin
	 * we will define a CONST
	 * such as plguin_installed
	 * that will be used weather plugin is installed or not
	 * ie define("editorspick_install","installed");
	 * is_installed('editorspic');
	 */
	function is_installed($plugin)
	{
		if(defined($plugin."_install"))
			return true;
		else
			return false;
	}
	
	
	/**
	 * Category Link is used to return
	 * Category based link
	 */
	function category_link($data,$type)
	{
		switch($type)
		{
			case 'video':case 'videos':case 'v':
			{
				
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/videos.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':case 'channel':case'c':case'user':
			{
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/channels.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			default:
			{
				
				if(THIS_PAGE=='photos')
					$type = 'photos';

				if(defined("IN_MODULE"))
				{
					$url = 'cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
					global $prefix_catlink;
					$url = $prefix_catlink.$url;
					
					$rm_array = array("cat","sort","time","page","seo_cat_name");
					$p = "";
					if($prefix_catlink)
						$rm_array[] = 'p';
					$plugURL = queryString($url,$rm_array);
					return $plugURL;
				}
								
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/'.$type.'.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
		}
	}
	
	/**
	 * Sorting Links is used to return
	 * Sorting based link
	 */
	function sort_link($sort,$mode='sort',$type)
	{
		switch($type)
		{
			case 'video':
			case 'videos':
			case 'v':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/videos.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':
			case 'channel':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/channels.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			
			default:
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
				
				if(THIS_PAGE=='photos')
					$type = 'photos';
				
				if(defined("IN_MODULE"))
				{
					$url = 'cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
					$plugURL = queryString($url,array("cat","sort","time","page","seo_cat_name"));
					return $plugURL;
				}
				
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/'.$type.'.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;		
		}
	}
	
	
	
	
	/**
	 * Function used to get flag options
	 */
	function get_flag_options()
	{
		$action = new cbactions();
		$action->init();
		return $action->report_opts;
	}
	
	/**
	 * Function used to display flag type
	 */
	function flag_type($id)
	{
		$flag_opts = get_flag_options();
		return $flag_opts[$id];
	}
	
	
	/**
	 * Function used to load captcha field
	 */
	function get_captcha()
	{
		global $Cbucket;
		if(count($Cbucket->captchas)>0)
		{
			return $Cbucket->captchas[0];
		}else
			return false;
	}
	
	/**
	 * Function used to load captcha
	 */
	define("GLOBAL_CB_CAPTCHA","cb_captcha");
	function load_captcha($params)
	{
		global $total_captchas_loaded;
		switch($params['load'])
		{
			case 'function':
			{
				if($total_captchas_loaded!=0)
					$total_captchas_loaded = $total_captchas_loaded+1;
				else
					$total_captchas_loaded = 1;
				$_COOKIE['total_captchas_loaded'] = $total_captchas_loaded;
				if(function_exists($params['captcha']['load_function']))
					return $params['captcha']['load_function']().'<input name="cb_captcha_enabled" type="hidden" id="cb_captcha_enabled" value="yes" />';
			}
			break;
			case 'field':
			{
				echo '<input type="text" '.$params['field_params'].' name="'.GLOBAL_CB_CAPTCHA.'" />';
			}
			break;
			
		}
	}
	

	
?>