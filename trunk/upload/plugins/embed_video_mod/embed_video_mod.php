<?php
/*
	Plugin Name: Embed Video Upload
	Description: This will let you upload videos using Embed Code
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/
*/


	$Cbucket->upload_opt_list['embed_code_div'] = 
							  array(
							   'title'	=> 'Embed Code',
							   'load_func'	=>	'load_embed_form',
							   );
if(post('verify_embed'))
{
	$embed_code = post('embed_code');
	if($embed_code)
		$_POST ['embed_code'] = base64_decode($embed_code);		
}

if(!function_exists('validate_embed_code'))
{
	
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
	
	
	/**
	 * Function used to validate embed code
	 */
	function validate_embed_code($val)
	{
	
		if(empty($val) || $val=='none')
		{
			return 'none';		
		}else{
			//$val = base64_decode($val);
			//Striping Slasshes as they are not required
			$val = stripslashes($val);
			//Removing spaces and non required code
			$val = preg_replace(array("/\r+/","/\n+/","/\t+/"),"",$val);
			//Removing Links
			$val = preg_replace('/<a href=(.*)>(.*)<\/a>/i','',$val);
			//Removing JS Codes
			$val = preg_replace('/<script[^>]*?>.*?<\/script>/si','',$val);		
			//Removing Iframes
			//$val = preg_replace('/<iframe(.*)><\/iframe>/i','',$val);
			//Removing Img Tags
			$val = preg_replace('/<img (.*) \/>/i','',$val);
			//Removing DIV Tags
			//$val = preg_replace('/<div(.*)><\/div>/i','',$val);
			//Just Get Data wrapped inside embed 
			//$val = preg_match('/<embed(.*)>(.*)<\/embed>/',$val,$matches);
			//$val = $matches[0];
			
			if(!stristr($val,'<embed') && !stristr($val,'<object') && !stristr($val,'<iframe') )
				e(lang('embed_code_invalid_err'));
			
			//Replacing Widht and Height 
			$pattern = array
			("/width=\"([0-9]+)\"/Ui","/width='([0-9]+)'/Ui","/height=\"([0-9]+)\"/Ui","/height='([0-9]+)'/Ui",
			 "/width:([0-9]+)px/Ui","/height:([0-9]+)px/Ui");
			$replace = array
			('width="{Width}"','width="{Width}"','height="{Height}"','height="{Height}"',
			 'width:{Width}px','height:{Height}px');
			
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
		if($params['class'])
			$class = ' '.$params['class'];
		assign('objId',RandomString(5));
		assign('class',$class);
		Template(PLUG_DIR.'/embed_video_mod/form.html',false);
	}
	
	$embed_field_array['embed_code'] = array
	(
	 	'title'		=>'Embed Code',
		'name'		=>'embed_code',
		'db_field'	=>'embed_code',
		'required'	=>'no',
		'validate_function'=>'validate_embed_code',
		'use_func_val' => true,
		'clean_func' => array('clean_embed_code'),
		'type'	=> 'textarea',
		'use_if_value' => true,
		'hint_2'=>'Type "none" to set as empty',
		'size'=>'45',
		'rows'=>5
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
		'use_if_value' => true,
		
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
	
	function clean_embed_code($input)
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
	function embed_video_check($vid)
	{
		global $myquery,$db;
		if(is_array($vid))
			$vdetails = $vid;
		else
			$vdetails = $myquery->get_video_details($vid);
			
		if(!empty($vdetails['embed_code']) && $vdetails['embed_code'] !=' ' && $vdetails['embed_code'] !='none')
		{
			//Parsing Emebd Codek, Getting Referal URL if possible and add AUTPLAY on of option 
			$ref_url = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
			$ref_url = $ref_url['url'];
			$db->update(tbl("video"),array("status","refer_url"),array('Successful',$ref_url)," videoid='$vid'");
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
				$h_w_r = array($data['width'],$data['height']);	
				$embed_code = str_replace($h_w_p,$h_w_r,$embed_code);
				$embed_code = unhtmlentities($embed_code);
				$embed_code = preg_replace('/<b>(.*<\/b>)?/','',$embed_code);
				
				//Checking for REF CODE , if its youtube, add AUTOPLAY accordingly)
				$ref = get_refer_url_from_embed_code($embed_code);
				if(!empty($ref) && $ref['website'] == "youtube")
				{
					//Add AutoPlay
					if(config("autoplay_video")=="yes")
						$autoplay = 1;
					else
						$autoplay = 0;
					$embed_code = preg_replace("/src=\"(.*)\"/Ui","src=\"$1&autoplay=".$autoplay."\"",$embed_code);
				}
				
				preg_match('/http:\/\/www\.youtube\.com\/v\/([a-zA-Z0-9_-]+)/',$embed_code,$ytmatches);
				
				$ytCode = $ytmatches[1];
				
				if(!$ytCode)
				{
					preg_match('/http:\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',$embed_code,$ytmatches);
					$ytCode = $ytmatches[1];
				}
				
				if(YOUTUBE_ENABLED=='yes' && $ytCode )
				{
					assign('youtube',$ytCode);
					assign('ytcode',$ytCode);
					return false;
				}
				
				$swfobj->EmbedCode($embed_code,$data['player_div']);
				return $swfobj->code;
			}
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get refer url from youtube embed code
	 */
	function get_refer_url_from_embed_code($code)
	{
		//ONLY POSSIBLE WITH YOUTUBE , MORE WILL BE ADDED LATER
		preg_match("/src=\"(.*)\"/Ui",$code,$matches);
		$src = $matches[1];
		//Checking for youtube
		preg_match("/youtube\.com/",$src,$ytcom);
		preg_match("/youtube-nocookie\.com/",$src,$ytnccom);
		
		if(!empty($ytcom[0]) || !empty($ytnccom[0]))
		{
			preg_match("/\/v\/([a-zA-Z0-9_-]+)/",$src,$srcs);
			$srcs = explode("&",$srcs[1]);
			$ytcode = $srcs[0];
			if(!$ytcode)
			{
				preg_match("/\/embed\/(.*)/",$src,$srcs);
				$srcs = explode("&",$srcs[1]);
				$ytcode = $srcs[0];
			}
			//Creating Youtube VIdeo URL 
			$yturl = "http://www.youtube.com/watch?v=".$ytcode;
			$results['url'] = $yturl;
			$results['ytcode'] = $ytcode;
			$results['website'] = 'youtube';
			return $results;
		}else
			return false;
			
	}
	
	//If Youtube
	function is_ref_youtube($url)
	{
		preg_match("/youtube\.com/",$url,$ytcom);
		return $ytcom;
	}
	
	register_after_video_upload_action('embed_video_check');
	register_custom_upload_field($embed_field_array);
	$Cbucket->add_header(PLUG_DIR.'/embed_video_mod/header.html');
	register_actions_play_video('play_embed_video');
	
}
	
?>