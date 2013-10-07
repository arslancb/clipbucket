<?php
/*
	Plugin Name: Link video
	Description: If you want to play videos via link, this plugin is a good option for you
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/
*/


	$Cbucket->upload_opt_list['link_video_link'] = 
							  array(
							   'title'	=> lang('remote_play'),
							   'load_func'	=>	'load_link_video_form',
							   );
		

	/**
	 * Function used create duration from input
	 * @param DURATION
	 */
	if(!function_exists('validate_duration'))
	{
		function validate_duration($time)
		{
			
			global $LANG;
			if(empty($time))
				return true;
			$time = explode(':',$time);
			if(count($time)>0 && is_array($time))
			{
				$sec = 0;
				$total = count($time);
				
				if($total==3)
				{
					$hrs = $time[0]*60*60;
					$mins = $time[1]*60;
					$secs = $time[2];
				}elseif($total==2)
				{
					$hrs = 0;
					$mins = $time[0]*60;
					$secs = $time[1];
				}else{
					$hrs = 0;
					$mins = 0;
					$secs = $time[0];
				}
				$sec = $hrs+$mins+$secs;			
				if(!empty($sec))
					return $sec;
				else
					e(lang('invalid_duration'));
			}else{
				if(is_numeric($time))
					return $time;
				else
					e(lang('invalid_duration'));
			}
		}
	}
	
	
	function check_remote_play_link($val)
	{
		//checking file exension
		$validExts = array('flv','mp4');
		$ext = getExt($val);
		if(!in_array($ext,$validExts) || !$val		
		|| 
		( !stristr($val,'http://') 
		&& !stristr($val,'https://')
		&& !stristr($val,'rtsp://')
		&& !stristr($val,'rtmp://') ))
		{
			e("Invalid video url");
			return false;
		}
		
		return true;
	}
	
	/**
	 * Function used to validate embed code
	 */
	function validate_video_link($val)
	{
	
		if(empty($val) || $val=='none')
		{
			return 'none';		
		}else{
			//checking file exension
			$validExts = array('flv','mp4');
			$ext = getExt($val);
			if(!in_array($ext,$validExts) 
				|| 
				( !stristr($val,'http://') 
				&& !stristr($val,'https://')
				&& !stristr($val,'rtsp://')
				&& !stristr($val,'rtmp://') ))
			{
				return false;
			}
			return $val;
		}
	}

	
	/**
	 * Function used to load embed form
	 */
	function load_link_video_form($params)
	{
		global $file_name;
		if($params['class'])
			$class = ' '.$params['class'];
		assign('objId',RandomString(5));
		assign('class',$class);
		Template(PLUG_DIR.'/cb_link_video/form.html',false);
	}
	
	$link_vid_field_array['remote_play_url'] = array
	(
	 	'title'		=>'Link to video',
		'name'		=>'remote_play_url',
		'db_field'	=>'remote_play_url',
		'required'	=>'no',
		'validate_function'=>'validate_video_link',
		'use_func_val' => true,
		'type'	=> 'textfield',
		'use_if_value' => true,
		'hint_2'=>'Type "none" to set as empty',
		'size'=>'45',
		'rows'=>5
	 );	
	
	$link_vid_field_array['duration'] = array
	(
	 	'title'		=>'Video duration',
		'name'		=>'duration',
		'db_field'	=>'duration',
		'required'	=>'no',
		'validate_function'=>'validate_duration',
		'use_func_val' => true,
		'display_admin'	=> 'no_display',
		'use_if_value' => true,
		
	 );
	
	$link_vid_field_array['thumb_file_field'] = array
	( 
	 	'title'	=> 'Thumb File',
		'type'	=> 'fileField',
		'name'	=> 'thumb_file',
		'required' => 'no',
		'validate_function' => 'upload_thumb',
		'display_admin'	=> 'no_display',
	);
	
	function clean_remote_code($input)
	{
		$input = htmlspecialchars($input);
		//if(!get_magic_quotes_gpc())
		//	$input = addslashes($input);
		return $input;
	}
	
	
	if(!function_exists('upload_thumb'))
	{
		function upload_thumb($array)
		{
			
			global $file_name,$LANG;
			
			//Get File Name
			$file 		= $array['name'];
			$ext 		= getExt($file);
			$image = new ResizeImage();
			
			if(!empty($file) && file_exists($array['tmp_name']) &&!error())
			{
				if($image->ValidateImage($array['tmp_name'],$ext)){
					$file = BASEDIR.'/files/thumbs/'.$_POST['file_name'].'.'.$ext;
					$bfile = BASEDIR.'/files/thumbs/'.$_POST['file_name'].'.-big.'.$ext;
					if(!file_exists($file))
					{
						move_uploaded_file($array['tmp_name'],$file);
						$image->CreateThumb($file,$bfile,config('big_thumb_width'),$ext,config('big_thumb_height'),false);
						$image->CreateThumb($file,$file,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
					}
				}else{
					e(lang('vdo_thumb_up_err'));
				}
			}else{
				return true;
			}
		}
	}
	
	
	/**
	 * Function used to check embed video
	 * if video is embeded , it will check its code 
	 * if everthing goes ok , it will change its status to successfull
	 * @param VID
	 */
	function remote_video_check($vid)
	{
		global $myquery,$db;
		if(is_array($vid))
			$vdetails = $vid;
		else
			$vdetails = $myquery->get_video_details($vid);
			
		if(!empty($vdetails['remote_play_url']) && $vdetails['remote_play_url'] !=' ' && $vdetails['remote_play_url'] !='none')
		{
			$db->update(tbl("video"),array("status"),array('Successful')," videoid='$vid'");
		}
	}
	
	
	/**
	 * Function used to play embed code
	 * @param Video details
	 */
	function play_remote_video($vdetails)
	{
		if(!empty($vdetails['remote_play_url']) && $vdetails['remote_play_url']!='none')
		{
			return $vdetails['remote_play_url'];
		}
	}
	
	
	$Cbucket->custom_video_file_funcs[] = 'play_remote_video';
	register_after_video_upload_action('remote_video_check');
	register_custom_upload_field($link_vid_field_array);
	$Cbucket->add_header(PLUG_DIR.'/cb_link_video/header.html');
	

	
?>