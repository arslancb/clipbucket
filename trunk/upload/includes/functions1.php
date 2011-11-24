<?php

	function getSmartyComments($params)
	{
		global $myquery;
		$comments  =  $myquery->getComments($params);
		
		if($params['assign'])
			assign($params['assign'],$comments);
		else
			return $comments;
	}
	
	/**
	* FUNCTION USED TO GET ADVERTISMENT
	* @param : array(Ad Code, LIMIT);
	*/
	function getAd($params)
	{
		global $adsObj;
		$data = '';
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '<div style="'.$params['style'].'" class="'.$params['class'].'" align="'.$params['align'].'">';
		$data .= ad($adsObj->getAd($params['place']));
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '</div>';
		return $data;
	}
	
	
	/**
	* Function Used to format video duration
	* @param : array(videoKey or ID,videok TITLE)
	*/
	
	function videoSmartyLink($params)
	{
		$link  =	VideoLink($params['vdetails'],$params['type']);
		if(!$params['assign'])
			return $link;
		else
			assign($params['assign'],$link);
	}
	
	/**
	* FUNCTION USED TO GET VIDEO RATING IN SMARTY
	* @param : array(pullRating($videos[$id]['videoid'],false,false,false,'novote');
	*/
	function pullSmartyRating($param)
	{
		return pullRating($param['id'],$param['show5'],$param['showPerc'],$aram['showVotes'],$param['static']);	
	}
	
	/**
	* FUNCTION USED TO CLEAN VALUES THAT CAN BE USED IN FORMS
	*/
	function cleanForm($string)
	{
		if(is_string($string))
			$string = htmlspecialchars($string);
		if(get_magic_quotes_gpc())
			if(!is_array($string))
			$string = stripslashes($string);			
		return $string;
	}
	function form_val($string){return cleanForm($string); }
	
	//Escaping Magic Quotes
	
	/**
	* FUNCTION USED TO MAKE TAGS MORE PERFECT
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tags text unformatted
	* returns tags formatted
	*/
	function genTags($tags,$sep=',')
	{
		//Remove fazool spaces
		$tags = preg_replace(array('/ ,/','/, /'),',',$tags);
		$tags = preg_replace( "`[,]+`" , ",", $tags);
		$tag_array = explode($sep,$tags);
		foreach($tag_array as $tag)
		{
			if(isValidtag($tag))
			{
				$newTags[] = $tag;
			}
			
		}
		//Creating new tag string
		if(is_array($newTags))
			$tagString = implode(',',$newTags);
		else
			$tagString = 'no-tag';
		return $tagString;
	}
	
	/**
	* FUNCTION USED TO VALIDATE TAG
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tag
	* return true or false
	*/
	function isValidtag($tag)
	{
		$disallow_array = array
		('of','is','no','on','off','a','the','why','how','what','in');
		if(!in_array($tag,$disallow_array) && strlen($tag)>2)
			return true;
		else
			return false;
	}
	
	
	/**
	* FUNCTION USED TO GET CATEGORY LIST
	*/
	function getCategoryList($params=false)
	{
		global $cats;
		$cats = "";
		
		$type = $params['type'];
		switch($type)
		{
			default:
			{
				 cb_call_functions('categoryListing',$params);
			}
			break;
			
			case "video":case "videos":
			case "v": 
			{
				global $cbvid;
				$cats = $cbvid->cbCategories($params);
			}
			break;
				
			case "users":case "user":
			case "u": case "channels": case "channels":
			{
				global $userquery;
				$cats = $userquery->cbCategories($params);
			}
			break;
			
			case "group":case "groups":
			case "g":
			{
				global $cbgroup;
				$cats = $cbgroup->cbCategories($params);
			}
			break;
			
			case "collection":case "collections":
			case "cl":
			{
				global $cbcollection;
				$cats = $cbcollection->cbCategories($params);
			}
			break;		
		}
		
		return $cats;
	}
	/*function getCategoryList($type='video',$with_all=false,$return_html=false)
	{
		$use_subs = config('use_subs');

		switch ($type)
		{
			case "video":
			default:
			{
				global $cbvid;

				if($return_html && $use_subs == "1") {
					$cats = $cbvid->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbvid->get_categories();
					
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			case "user":
			{
				global $userquery;

				
				if($return_html && $use_subs == "1") {
					$cats = $userquery->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $userquery->get_categories();
					
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			
			case "group":
			case "groups":
			{
				global $cbgroup;
				
				
				if($return_html && $use_subs == "1") {
					$cats = $cbgroup->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbgroup->get_categories();
						
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			
			case "collection":
			case "collections":
			{
				global $cbcollection;
				
				
				if($return_html && $use_subs == "1") {
					$cats = $cbcollection->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbcollection->get_categories();
						
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
		}
	}*/
	function cb_bottom()
	{
		//Woops..its gone
	}
	
	
	function getSmartyCategoryList($params)
	{
		return getCategoryList($params);
	}
	
	
	//Function used to register function as multiple modifiers
	
	
	
	/**
	* Function used to insert data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : extra params
	*/
	function dbInsert($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		$db->insert($tbl,$flds,$vls,$ep);
	}
	
	/**
	* Function used to Update data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : Condition params
	* @params : Extra params
	*/
	function dbUpdate($tbl,$flds,$vls,$cond,$ep=NULL)
	{
		global $db ;
		return $db->update($tbl,$flds,$vls,$cond,$ep);		
	}
	
	
	
	/**
	* Function used to Delete data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @params : Extra params
	*/
	function dbDelete($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		return $db->delete($tbl,$flds,$vls,$ep);		
	}
	
	
	/**
	 **
	 */
	function cbRocks()
	{
		define("isCBSecured",TRUE); 
		//echo cbSecured(CB_SIGN);
	}
	
	/**
	 * Insert Id
	 */
	 function get_id($code)
	 {
		 global $Cbucket;
		 $id = $Cbucket->ids[$code];
		 if(empty($id)) $id = $code;
		 return $id;
	 }
	 
	/**
	 * Set Id
	 */
	 function set_id($code,$id)
	 {
		 global $Cbucket;
		 return $Cbucket->ids[$code]=$id;
	 }
	 
	
	/**
	 * Function used to select data from database
	 */
	function dbselect($tbl,$fields='*',$cond=false,$limit=false,$order=false,$p=false)
	{
		global $db;
		return $db->dbselect($tbl,$fields,$cond,$limit,$order,$p);
	}
	
	
	/**
	 * Function used to count fields in mysql
	 * @param TABLE NAME
	 * @param Fields
	 * @param condition
	 */
	function dbcount($tbl,$fields='*',$cond=false)
	{
		global $db;
		if($cond)
			$condition = " Where $cond ";
		$query = "Select Count($fields) From $tbl $condition";
		$result = $db->Execute($query);
		$db->total_queries++;
		$db->total_queries_sql[] = $query;
		return $result->fields[0];
	}
	
	/**
	 * An easy function for erorrs and messages (e is basically short form of exception)
	 * I dont want to use the whole Trigger and Exception code, so e pretty works for me :D
	 * @param TEXT $msg
	 * @param TYPE $type (e for Error, m for Message
	 * @param INT $id Any Predefined Message ID
	 */
	
	function e($msg=NULL,$type='e',$id=NULL)
	{
		global $eh;
		if(!empty($msg))
			return $eh->e($msg,$type,$id);
	}
	
	
	/**
	 * Function used to get subscription template
	 */
	function get_subscription_template()
	{
		global $LANG;
		return lang('user_subscribe_message');
	}
	
	
	/**
	 * Short form of print_r as pr
	 */
	function pr($text,$wrap_pre=false)
	{
		if(!$wrap_pre)
		print_r($text);
		else
		{
			echo "<pre>";
			print_r($text);
			echo "</pre>";
		}
	}
	
	
	/**
	 * This function is used to call function in smarty template
	 * This wont let you pass parameters to the function, but it will only call it
	 */
	function FUNC($params)
	{
		global $Cbucket;
		//Function used to call functions by
		//{func namefunction_name}
		// in smarty
		$func=$params['name'];
		if(function_exists($func))
			$func();
	}
	
	/**
	 * Function used to get userid anywhere 
	 * if there is no user_id it will return false
	 */
	function user_id()
	{
		global $userquery;
		if($userquery->userid !='' && $userquery->is_login) return $userquery->userid; else false;
	}
	//replica
	function userid(){return user_id();}
	
	/**
	 * Function used to get username anywhere 
	 * if there is no usern_name it will return false
	 */
	function user_name()
	{
		global $userquery;
		if($userquery->user_name)
			return $userquery->user_name;
		else
			return $userquery->get_logged_username();
	}
	function username(){return user_name();}
	
	/**
	 * Function used to check weather user access or not
	 */
	function has_access($access,$check_only=TRUE,$verify_logged_user=true)
	{
		global $userquery;
		
		return $userquery->login_check($access,$check_only,$verify_logged_user);
	}
	
	/**
	 * Function used to return mysql time
	 * @author : Fwhite
	 */
	function NOW()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	
	/**
	 * Function used to get Regular Expression from database
	 * @param : code
	 */
	function get_re($code)
	{
		global $db;
		$results = $db->select(tbl("validation_re"),"*"," re_code='$code'");
		if($db->num_rows>0)
		{
			return $results[0]['re_syntax'];
		}else{
			return false;
		}
	}
	function get_regular_expression($code)
	{
		return get_re($code); 
	}
	
	/**
	 * Function used to check weather input is valid or not
	 * based on preg_match
	 */
	function check_re($syntax,$text)
	{
		preg_match('/'.$syntax.'/i',$text,$matches);
		if(!empty($matches[0]))
		{
			return true;
		}else{
			return false;
		}
	}
	function check_regular_expression($code,$text)
	{
		return check_re($code,$text); 
	}
	
	/**
	 * Function used to check field directly
	 */
	function validate_field($code,$text)
	{
		$syntax =  get_re($code);
		if(empty($syntax))
			return true;
		return check_regular_expression($syntax,$text);
	}
	
	function is_valid_syntax($code,$text)
	{
		if(DEVELOPMENT_MODE && DEV_INGNORE_SYNTAX)
			return true;
		return validate_field($code,$text);
	}
	
	/**
	 * Function used to apply function on a value
	 */
	function is_valid_value($func,$val)
	{
		if(!function_exists($func))
			return true;
		elseif(!$func($val))
			return false;
		else
			return true;
	}
	
	function apply_func($func,$val)
	{
		if(is_array($func))
		{
			foreach($func as $f)
				if(function_exists($f))
					$val = $f($val);
		}else{
			$val = $func($val);
		}
		return $val;
	}
	
	/**
	 * Function used to validate YES or NO input
	 */
	function yes_or_no($input,$return=yes)
	{
		$input = strtolower($input);
		if($input!=yes && $input !=no)
			return $return;
		else
			return $input;
	}
	
	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_vid_category($array=NULL)
	{
		global $myquery,$LANG,$cbvid;
		if($array==NULL)
			$array = $_POST['category'];
		if(count($array)==0)
			return false;
		else
		{
			
			foreach($array as $arr)
			{
				if($cbvid->category_exists($arr))
					$new_array[] = $arr;
			}
		}
		if(count($new_array)==0)
		{
			e(lang('vdo_cat_err3'));
			return false;
		}elseif(count($new_array)>ALLOWED_VDO_CATS)
		{
			e(sprintf(lang('vdo_cat_err2'),ALLOWED_VDO_CATS));
			return false;
		}
			
		return true;
	}
	
	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_group_category($array=NULL)
	{
		global $cbgroup;
		return $cbgroup->validate_group_category($array);
	}

	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_collection_category($array=NULL)
	{
		global $cbcollection;
		return $cbcollection->validate_collection_category($array);
	}	
		
	/**
	 * Function used to check videokey exists or not
	 * key_exists
	 */
	function vkey_exists($key)
	{
		global $db;
		$db->select(tbl("video"),"videokey"," videokey='$key'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to check file_name exists or not
	 * as its a unique name so it will not let repost the data
	 */
	function file_name_exists($name)
	{
		global $db;
		$results = $db->select(tbl("video"),"videoid,file_name"," file_name='$name'");
		
		if($db->num_rows >0)
			return $results[0]['videoid'];
		else
			return false;
	}
	
	
	
	/**
	 * Function used to get video from conversion queue
	 */
	function get_queued_video($update=TRUE,$fileName=NULL)
	{
		global $db;
		$max_conversion = config('max_conversion');
		$max_conversion = $max_conversion ? $max_conversion : 2;
		$max_time_wait = config('max_time_wait'); //Maximum Time Wait to make PRocessing Video Automatcially OK
		$max_time_wait = $max_time_wait ? $max_time_wait  : 7200;
		
		//First Check How Many Videos Are In Queu Already
		$processing = $db->count(tbl("conversion_queue"),"cqueue_id"," cqueue_conversion='p' ");
		if(true)
		{
			if($fileName)
			{
				$queueName = getName($fileName);
				$ext = getExt($fileName);
				$fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
			}
			$results = $db->select(tbl("conversion_queue"),"*","cqueue_conversion='no' $fileNameQuery",1);
			$result = $results[0];
			if($update)
			$db->update(tbl("conversion_queue"),array("cqueue_conversion","time_started"),array("p",time())," cqueue_id = '".$result['cqueue_id']."'");
			return $result;
		}else
		{
			//Checking if video is taking more than $max_time_wait to convert so we can change its time to 
			//OK Automatically
			//Getting All Videos That are being processed
			$results = $db->select(tbl("conversion_queue"),"*"," cqueue_conversion='p' ");
			foreach($results as $vid)
			{
				if($vid['time_started'])
				{
					if($vid['time_started'])
						$time_started = $vid['time_started'];
					else
						$time_started = strtotime($vid['date_added']);
					
					$elapsed_time = time()-$time_started;
					
					if($elapsed_time>$max_time_wait)
					{
						//CHanging Status TO OK
						$db->update(tbl("conversion_queue"),array("cqueue_conversion"),
						array("yes")," cqueue_id = '".$result['cqueue_id']."'");
					}
				}
			}
			return false;
		}
	}

	
	
	/**
	 * Function used to get video being processed
	 */
	function get_video_being_processed($fileName=NULL)
	{
		global $db;
		
		if($fileName)
		{
			$queueName = getName($fileName);
			$ext = getExt($fileName);
			$fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
		}
			
		$results = $db->select(tbl("conversion_queue"),"*","cqueue_conversion='p' $fileNameQuery");
		return $results;
	}
	
	function get_video_details($vid=NULL)
	{
		global $myquery;
		if(!$vid)
			global $vid;	
		return $myquery->get_video_details($vid);
	}
	
	
	
	/**
	 * Function used to get all video files
	 * @param Vdetails
	 * @param $count_only
	 * @param $with_path
	 */
	function get_all_video_files($vdetails,$count_only=false,$with_path=false)
	{
		$details = get_video_file($vdetails,true,$with_path,true,$count_only);
		if($count_only)
			return count($details);
		return $details;
	}
	function get_all_video_files_smarty($params)
	{
		$vdetails = $params['vdetails'];
		$count_only = $params['count_only'];
		$with_path = $params['with_path'];
		return get_all_video_files($vdetails,$count_only,$with_path);
	}
	
	/**
	 * Function use to get video files
	 */
	function get_video_file($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false)
	{	
		global $Cbucket;
		# checking if there is any other functions
		# available
		if(is_array($Cbucket->custom_video_file_funcs))
		foreach($Cbucket->custom_video_file_funcs as $func)
			if(function_exists($func))
			{
				$func_returned = $func($vdetails, $hq);
				if($func_returned)
				return $func_returned;
			}
		
		#Now there is no function so lets continue as (WITH .files)
		if($vdetails['file_name'])
			$vid_files = glob(VIDEOS_DIR."/".$vdetails['file_name'].".*");
		
		#Now there is no function so lets continue as (WITH - files)
		if($vdetails['file_name'])
			$vid_files_more = glob(VIDEOS_DIR."/".$vdetails['file_name']."-*");
		
		if($vid_files && $vid_files_more)
			$vid_files = array_merge($vid_files,$vid_files_more);


		#replace Dir with URL
		if(is_array($vid_files))
		foreach($vid_files as $file)
		{
			$files_part = explode('/',$file);
			$video_file = $files_part[count($files_part)-1];
			
			if($with_path)
				$files[]	= VIDEOS_URL.'/'.$video_file;
			else
				$files[]	= $video_file;
		}
		
		if(count($files)==0 && !$multi && !$count_only)
		{
			if($return_default)
			{
				if($with_path)
					return VIDEOS_URL.'/no_video.flv';
				else
					return 'no_video.flv';
			}else{
				return false;
			}
		}else{
			if($multi)
				return $files;
			if($count_only)
				return count($files);
			
			foreach($files as $file)
			{
				if($hq)
				{
					if(getext($file)=='mp4' && !strstr($file,'-m'))
					{
	 					return $file;
						break;
					}
				}else{
					return $file;
					break;
				}
			}
			return $files[0];
		}
	}
	
	/**
	 * FUnction used to get HQ ie mp4 video
	 */
	function get_hq_video_file($vdetails,$return_default=true)
	{
		return get_video_file($vdetails,$return_default,true,false,false,true);
	}
	
	
	/**
	 * Function used to display flash player for ClipBucket video
	 */
	function flashPlayer($param)
	{
		global $Cbucket,$swfobj;
		
		$param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';
		
		$key 		= $param['key'];
		$flv 		= $param['flv'].'.flv';
		$code 		= $param['code'];
		$flv_url 	= $file;
		$embed 		= $param['embed'];
		$code 		= $param['code'];
		$height 	= $param['height'] ? $param['height'] : config('player_height');
		$width 		= $param['width'] ? $param['width'] : config('player_width');
		$param['height'] = $height;
		$param['width'] = $width ;
		
		if(!$param['autoplay'])
		$param['autoplay'] = config('autoplay_video');
		
		assign('player_params',$param);
		if(count($Cbucket->actions_play_video)>0)
		{
	 		foreach($Cbucket->actions_play_video as $funcs )
			{
				
				if(function_exists($funcs))
				{
					$func_data = $funcs($param);
				}
				if($func_data)
				{
					$player_code = $func_data;
					break;
				}
			}
		}
		
		if(function_exists('cbplayer') && empty($player_code))
			$player_code = cbplayer($param,true);
		
		global $pak_player;
		
		if($player_code)
		if(!$pak_player && $show_player)
		{
			assign("player_js_code",$player_code);
			Template(PLAYER_DIR.'/player.html',false);
			return false;
		}else
		{
			return false;
		}
		
		return blank_screen($param);
	}
	
	
	/**
	 * FUnctiuon used to plya HQ videos
	 */
	function HQflashPlayer($param)
	{
		return flashPlayer($param);
	}
	
	
	/**
	 * Function used to get player from website settings
	 */
	function get_player()
	{
		global $Cbucket;
		return $Cbucket->configs['player_file'];
	}
	
	
	/**
	 * Function used to get user avatar
	 * @param ARRAY $userdetail
	 * @param SIZE $int
	 */
	function avatar($param)
	{
		global $userquery;
		$udetails = $param['details'];
		$size = $param['size'];
		$uid = $param['uid'];
		return $userquery->avatar($udetails,$size,$uid);
	}
	
	
	/**
	 * This funcion used to call function dynamically in smarty
	 */
	function load_form($param)
	{
		$func = $param['name'];
		if(function_exists($func))
			return $func($param);
	}
	
	
	
	/**
	 * Function used to get PHP Path
	 */
	 function php_path()
	 {
		 if(PHP_PATH !='')
			 return PHP_PATH;
		 else
		 	return "/usr/bin/php";
	 }
	 
	 /**
	  * Functon used to get binary paths
	  */
	 function get_binaries($path)
	 {
		 if(is_array($path))
		 {
			 $type = $path['type'];
			 $path = $path['path'];
		 }
		
		if($type=='' || $type=='user')
		{
			$path = strtolower($path);
			switch($path)
			{
				case "php":
				return php_path();
				break;
				
				case "mp4box":
				return config("mp4boxpath");
				break;
				
				case "flvtool2":
				return config("flvtool2path");
				break;
				
				case "ffmpeg":
				return config("ffmpegpath");
				break;
			}
		}else{
			$path = strtolower($path);
			switch($path)
			{
				case "php":
				$return_path = shell_output("which php");
				if($return_path)
					return $return_path;
				else
					return "Unable to find PHP path";
				break;
				
				case "mp4box":
				$return_path =  shell_output("which MP4Box");
				if($return_path)
					return $return_path;
				else
					return "Unable to find mp4box path";
				break;
				
				case "flvtool2":
				$return_path =  shell_output("which flvtool2");
				if($return_path)
					return $return_path;
				else
					return "Unable to find flvtool2 path";
				break;
				
				case "ffmpeg":
				$return_path =  shell_output("which ffmpeg");
				if($return_path)
					return $return_path;
				else
					return "Unable to find ffmpeg path";
				break;
			}
		}
	 }
	 
	 
	/**
	 * Function in case htmlspecialchars_decode does not exist
	 */
	function unhtmlentities ($string)
	{
		$trans_tbl =get_html_translation_table (HTML_ENTITIES );
		$trans_tbl =array_flip ($trans_tbl );
		return strtr ($string ,$trans_tbl );
	}
	
	
	
	/**
	 * Function used to update processed video
	 * @param Files details
	 */
	function update_processed_video($file_array,$status='Successful',$ingore_file_status=false,$failed_status='')
	{
		global $db;
		$file = $file_array['cqueue_name'];
		$array = explode('-',$file);
		
		if(!empty($array[0]))
			$file_name = $array[0];
		$file_name = $file;
		
		$file_path = VIDEOS_DIR.'/'.$file_array['cqueue_name'].'.flv';
		$file_size = @filesize($file_path);
		
		if(file_exists($file_path) && $file_size>0 && !$ingore_file_status)
		{		
			$stats = get_file_details($file_name);
			
			//$duration = $stats['output_duration'];
			//if(!$duration)
			//	$duration = $stats['duration'];
			
			$duration = parse_duration(LOGS_DIR.'/'.$file_array['cqueue_name'].'.log');
				
			$db->update(tbl("video"),array("status","duration","failed_reason"),
			array($status,$duration,$failed_status)," file_name='".$file_name."'");
		}else
		{
			$stats = get_file_details($file_name);
			
			//$duration = $stats['output_duration'];
			//if(!$duration)
			//	$duration = $stats['duration'];
			
			$duration = parse_duration(LOGS_DIR.'/'.$file_array['cqueue_name'].'.log');
			
			$db->update(tbl("video"),array("status","duration","failed_reason"),
			array('Failed',$duration,$failed_status)," file_name='".$file_name."'");
		}
	}
	
	
	/**
	 * This function will activate the video if file exists
	 */
	function activate_video_with_file($vid)
	{
		global $db;
		$vdetails = get_video_details($vid);
		$file_name = $vdetails['file_name'];
		$results = $db->select(tbl("conversion_queue"),"*"," cqueue_name='$file_name' AND cqueue_conversion='yes'");
		$result = $results[0];
		update_processed_video($result);							   
	}
	
	
	/**
	 * Function Used to get video file stats from database
	 * @param FILE_NAME
	 */
	function get_file_details($file_name)
	{
		global $db;
		//$result = $db->select(tbl("video_files"),"*"," id ='$file_name' OR src_name = '$file_name' ");
		//Reading Log File
		$file = LOGS_DIR.'/'.$file_name.'.log';
		if(!file_exists($file))
			$file = $file_name;
		if(file_exists($file))
		{
			$data = file_get_contents($file);
			//$file = file_get_contents('1260270267.log');
			
			preg_match_all('/(.*) : (.*)/',trim($data),$matches);
			
			$matches_1 = ($matches[1]);
			$matches_2 = ($matches[2]);
			
			for($i=0;$i<count($matches_1);$i++)
			{
				$statistics[trim($matches_1[$i])] = trim($matches_2[$i]);
			}
			if(count($matches_1)==0)
			{
				return false;
			}
			$statistics['conversion_log'] = $data;
			return $statistics;
		}else
			return false;
	}
	
	function parse_duration($log)
	{
		$duration = false;
		$log_details = get_file_details($log);
		$duration = $log['output_duration'];
		if(!$duration || !is_numeric($duration))
				$duration = $log['duration'];

		if(!$duration || !is_numeric($duration))
		{
			if(file_exists($log))
				$log_content = file_get_contents($log);
			
			//Parse duration..
			preg_match_all('/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9.]{1,5})/i',$log_content,$matches);
			
			unset($log_content);
			
			//Now we will multiple hours, minutes accordingly and then add up with seconds to 
			//make a single variable of duration
			
			$hours = $matches[1][0];
			$minutes = $matches[2][0];
			$seconds = $matches[3][0];
			
			$hours = $hours * 60 * 60;
			$minutes = $minutes * 60;
			$duration = $hours+$minutes+$seconds;
			
			$duration;
		}	
		return $duration;
	}
	
	
	/**
	 * Function used to execute command in background
	 */
	function bgexec($cmd) {
		if (substr(php_uname(), 0, 7) == "Windows"){
			//exec($cmd." >> /dev/null &");
			exec($cmd);
			//pclose(popen("start \"bla\" \"" . $exe . "\" " . escapeshellarg($args), "r")); 
		}else{
			exec($cmd . " > /dev/null &");  
		}
	}
	
	
	/**
	 * Function used to get thumbnail number from its name
	 * Updated: If we provide full path for some reason and 
	 * web-address has '-' in it, this means our result is messed.
	 * But we know our number will always be in last index
	 * So wrap it with end() and problem solved.
	 */
	function get_thumb_num($name)
	{
		$list = end(explode('-',$name));
		$list = explode('.',$list);
		return  $list[0];
	}
	
	
	/**
	 * Function used to remove thumb
	 */
	function delete_video_thumb($file)
	{
		global $LANG;
		$path = THUMBS_DIR.'/'.$file;
		if(file_exists($path))
		{
			unlink($path);
			e(lang('video_thumb_delete_msg'),'m');
		}else{
			e(lang('video_thumb_delete_err'));
		}
	}
	 
	 
	/**
	 * Function used to get array value
	 * if you know partial value of array and wants to know complete 
	 * value of an array, this function is being used then
	 */
	function array_find($needle, $haystack)
	{
	   foreach ($haystack as $item)
	   {
		  if (strpos($item, $needle) !== FALSE)
		  {
			 return $item;
			 break;
		  }
	   }
	}

	
	
	/**
	 * Function used to give output in proper form 
	 */
	function input_value($params)
	{
		$input = $params['input'];
		$value = $input['value'];
		
		if($input['value_field']=='checked')
			$value = $input['checked'];
			
		if($input['return_checked'])
			return $input['checked'];
			
		if(function_exists($input['display_function']))
			return $input['display_function']($value);
		elseif($input['type']=='dropdown')
		{
			if($input['checked'])
				return $value[$input['checked']];
			else
				return $value[0];
		}else
			return $input['value'];
	}
	
	/**
	 * Function used to convert input to categories
	 * @param input can be an array or #12# like
	 */
	function convert_to_categories($input)
	{
		if(is_array($input))
		{
			foreach($input as $in)
			{		
				if(is_array($in))
				{
					foreach($in as $i)
					{
						if(is_array($i))
						{
							foreach($i as $info)
							{
								$cat_details = get_category($info);
								$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
							}
						}elseif(is_numeric($i)){
							$cat_details = get_category($i);
							$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
						}
					}
				}elseif(is_numeric($in)){
					$cat_details = get_category($in);
					$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
				}
			}
		}else{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
			foreach($cat_array as $i)
			{
				$cat_details = get_category($i);
				$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
			}
		}
		
		$count = 1;
		if(is_array($cat_array))
		{
			foreach($cat_array as $cat)
			{
				echo '<a href="'.$cat[0].'">'.$cat[1].'</a>';
				if($count!=count($cat_array))
				echo ', ';
				$count++;
			}
		}
	}
	
	
	
	/**
	 * Function used to get categorie details
	 */
	function get_category($id)
	{
		global $myquery;
		return $myquery->get_category($id);
	}
	
	
	/**
	 * Sharing OPT displaying
	 */
	function display_sharing_opt($input)
	{
		foreach($input as $key => $i)
		{
			return $key;
			break;
		}
	}
	
	/**
	 * Function used to get number of videos uploaded by user
	 * @param INT userid
	 * @param Conditions
	 */
	function get_user_vids($uid,$cond=NULL,$count_only=false)
	{
		global $userquery;
		return $userquery->get_user_vids($uid,$cond,$count_only);
	}
	
	
	
	/**
	 * Function used to get error_list
	 */
	function error_list()
	{
		global $eh;
		return $eh->error_list;
	}
	
	
	/**
	 * Function used to get msg_list
	 */
	function msg_list()
	{
		global $eh;
		return $eh->message_list;
	}
	
	
	/**
	 * Function used to add tempalte in display template list
	 * @param File : file of the template
	 * @param Folder : weather to add template folder or not
	 * if set to true, file will be loaded from inside the template
	 * such that file path will becom $templatefolder/$file
	 * @param follow_show_page : this param tells weather to follow ClipBucket->show_page
	 * variable or not, if show_page is set to false and follow is true, this template will not load
	 * otherwise there it WILL
	 */
	function template_files($file,$folder=false,$follow_show_page=true)
	{
		global $ClipBucket;
		if(!$folder)
			$ClipBucket->template_files[] = array('file' => $file,'follow_show_page'=>$follow_show_page);
		else
			$ClipBucket->template_files[] = array('file'=>$file,
			'folder'=>$folder,'follow_show_page'=>$follow_show_page);
	}
	
	/**
	 * Function used to include file
	 */
	function include_template_file($params)
	{
		$file = $params['file'];
		
		if(file_exists(LAYOUT.'/'.$file))
			Template($file);
		elseif(file_exists($file))
			Template($file,false);
	}
	
	
	
?>