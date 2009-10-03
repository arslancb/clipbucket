<?php
/*
	Plugin Name: Embed Video Upload
	Description: This will let you upload videos using Embed Code
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://labguru.com/
*/


	$Cbucket->upload_opt_list['embed_code_div'] = 
							  array(
							   'title'	=> 'Embed Code',
							   'load_func'	=>	'load_embed_form',
							   );
		

if(!function_exists('validate_embed_code'))
{
	
	/**
	 * Function used create duration from input
	 * @param DURATION
	 */
	function validate_duration($time)
	{
		global $LANG;
		if(empty($time))
			return true;
		$time = explode(':',$time);
		if(count($time)>0)
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
				e($LANG['invalid_duration']);
		}else{
			if(is_numeric($time))
				return $time;
			else
				e($LANG['invalid_duration']);
		}
	}
	
	
	/**
	 * Function used to validate embed code
	 */
	function validate_embed_code($val)
	{
		global $LANG;
	
		if(empty($val) || $val=='none')
		{
			return 'none';		
		}else{
			//Removing spaces and non required code
			$val = preg_replace(array("/\r+/","/\n+/","/\t+/"),"",$val);
			//Removing Links
			$val = preg_replace('/<a href=(.*)>(.*)<\/a>/','',$val);
			//Removing JS Codes
			$val = preg_replace('/<script[^>]*?>.*?<\/script>/si','',$val);		
			//Removing Iframes
			$val = preg_replace('/<iframe(.*)><\/iframe>/','',$val);
			//Removing Img Tags
			$val = preg_replace('/<img (.*) \/>/','',$val);
			
			if(!stristr($val,'<embed')&&!stristr($val,'<object') &&!stristr($val,'<div'))
				e($LANG['embed_code_invalid_err']);
			
			//Replacing Widht and Height 
			$pattern = array
			('/width="([0-9]+)"/ui',"/width='([0-9]+)'/ui",'/height="([0-9]+)"/ui',"/height='([0-9]+)'/ui",
			 '/width:([0-9]+)px/ui','/height:([0-9]+)px/ui');
			$replace = array
			('width="{Width}"','width="{Width}"','height="{Height}"','height="{Height}"',
			 '/width:{Width}px/ui','/height:{Height}px/ui');
			
			$val = preg_replace($pattern,$replace,$val);
			
			return $val;
		}
	}

	
	/**
	 * Function used to load embed form
	 */
	function load_embed_form($params)
	{
		global $file_name;
		echo '<div class="upload_form_div">';
		echo '<span class="header2">Please enter embed code</span><br>';
		echo '<textarea name="embed_code" cols="30" id="embed_code" rows="3" class="upload_input textarea"></textarea>';
		echo '<br><br>';
		echo '<span class="header2">Please Enter video duration</span><br>';
		echo '<label for="duration">HH:MM:SS</label><input type="text" name="duration" id="duration" size="15"  class="upload_input"/>';
		echo '<br><br>';
		echo '<span class="header2">Please select video thumb</span><br>';
		echo '<input type="hidden" name="step_2" value="yes" />';
		echo '<input name="thumb_file" type="file"  class="upload_input filefield" id="thumb_file" />';
		echo '<div align="right"><input type="button" name="embed_upload" id="embed_upload" value="Upload" onClick="check_embed_code()"/></div>';
		echo '</div>';
	}
	
	$embed_field_array['embed_code'] = array
	(
	 	'title'		=>'Embed Code',
		'name'		=>'embed_code',
		'db_field'	=>'embed_code',
		'required'	=>'no',
		'validate_function'=>'validate_embed_code',
		'use_func_val' => true,
		'clean_func' => array('htmlspecialchars'),
		'type'	=> 'textarea',
		
	 );
	
	$embed_field_array['duration'] = array
	(
	 	'title'		=>'Video duration',
		'name'		=>'duration',
		'db_field'	=>'duration',
		'required'	=>'no',
		'validate_function'=>'validate_duration',
		'use_func_val' => true,
		'display_admin'	=> 'no_display',
		
	 );
	
	$embed_field_array['thumb_file_field'] = array
	( 
	 	'title'	=> 'Thumb File',
		'type'	=> 'fileField',
		'name'	=> 'thumb_file',
		'required' => 'no',
		'validate_function' => 'upload_thumb',
		'display_admin'	=> 'no_display',
	);
	
	function upload_thumb($array)
	{
		global $file_name,$LANG;
		
		//Get File Name
		$file 		= $array['name'];
		$ext 		= getExt($file);
		$image = new ResizeImage();
		
		if(!empty($file) && file_exists($array['tmp_name']))
		{
			if($image->ValidateImage($array['tmp_name'],$ext)){
				$file = BASEDIR.'/files/thumbs/'.$_POST['file_name'].'.'.$ext;
				if(!file_exists($file))
				{
					move_uploaded_file($array['tmp_name'],$file);
					$image->CreateThumb($file,$file,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
				}
			}else{
				e($LANG['vdo_thumb_up_err']);
			}
		}else{
			return true;
		}
	}
	
	
	/**
	 * Function used to check embed video
	 * if video is embeded , it will check its code 
	 * if everthing goes ok , it will change its status to successfull
	 * @param VID
	 */
	function embed_video_check($vid)
	{
		global $myquery,$db;
		$vdetails = $myquery->get_video_details($vid);
		if(!empty($vdetails['embed_code']) && $vdetails['embed_code'] !=' ')
		{
			$db->Execute("UPDATE video SET status='Successful' WHERE videoid='$vid'");
		}
	}
	
	
	/**
	 * Function used to play embed code
	 * @param Video details
	 */
	function play_embed_video($data)
	{
		global $swfobj;
		$vdetails = $data['vdetails'];
		$file = get_video_file($vdetails,false,false);
		if(!$file || $file=='no_video.flv')
		{
			if(!empty($vdetails['embed_code']) && $vdetails['embed_code']!='none')
			{
				$embed_code = $vdetails['embed_code'];
				//Replacing Height And Width
				$h_w_p = array("{Width}","{Height}");
				$h_w_r = array(config('player_width'),config('player_height'));	
				$embed_code = str_replace($h_w_p,$h_w_r,$embed_code);
				$swfobj->EmbedCode(unhtmlentities($embed_code),$data['player_div']);
				return $swfobj->code;
			}
		}else{
			return false;
		}
	}
	
	register_after_video_upload_action('embed_video_check');
	register_custom_upload_field($embed_field_array);
	$Cbucket->add_header(PLUG_DIR.'/embed_video_mod/header.html');
	register_actions_play_video('play_embed_video');
	
}
	
?>