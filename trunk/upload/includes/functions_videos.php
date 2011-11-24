<?php

/**
 * file_name : functions_videos.php
 * This function file contains all functions that are related to video section
 * @Author : Arslan
 * @Script : ClipBucket
 * @Since : 2.7
 */


/**
 * Get old time format
 *
 * ClipBucket uses different functions to convert time
 * This function simply converts seconds in MM:SS format
 * its old because it does not support Hours
 *
 * @since 1.6
 *
 * @param int $temps Duration of video in seconds
 * @return STRING Duration of video in mm:ss format
 */
 
function old_set_time($temps)
{
	round($temps);
	$heures = floor($temps / 3600);
	$minutes = round(floor(($temps - ($heures * 3600)) / 60));
	if ($minutes < 10)
			$minutes = "0" . round($minutes);
	$secondes = round($temps - ($heures * 3600) - ($minutes * 60));
	if ($secondes < 10)
			$secondes = "0" .  round($secondes);
	return $minutes . ':' . $secondes;
}

/**
 * Get video duration in H:M:S format
 *
 * This function works the same as old_set_time() in addition 
 * of Hours format. It also converts the video duration in 
 * H:M:S format
 *
 * @since : 2.x
 * 
 * @param $sec INT video duration in seconds
 * @param $padHours BOLLEAN weather to display hours or not
 * @return STRING video duration in H:M:S format
 */
 
function SetTime($sec, $padHours = true) {

	if($sec < 3600)
		return old_set_time($sec);
		
	$hms = "";

	// there are 3600 seconds in an hour, so if we
	// divide total seconds by 3600 and throw away
	// the remainder, we've got the number of hours
	$hours = intval(intval($sec) / 3600);

	// add to $hms, with a leading 0 if asked for
	$hms .= ($padHours)
		  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
		  : $hours. ':';

	// dividing the total seconds by 60 will give us
	// the number of minutes, but we're interested in
	// minutes past the hour: to get that, we need to
	// divide by 60 again and keep the remainder
	$minutes = intval(($sec / 60) % 60);

	// then add to $hms (with a leading 0 if needed)
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

	// seconds are simple - just divide the total
	// seconds by 60 and keep the remainder
	$seconds = intval($sec % 60);

	// add to $hms, again with a leading 0 if needed
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

	return $hms;
}



/**
 * Get thumbnails of a video
 * 
 * This function will get all video thumbnails
 * and list them in array, you can either get single
 * thumb or number or size. This function has few limitations
 * that we will try to cover in upcoming updates.
 *
 * @since 2.x
 * @uses default_thumb();
 * @uses MyQuery->get_video_details();
 *
 * @param $vdetails ARRAY video details, array('videod','title'...) or it can be just STRING videoid
 * @param $num STRING number of thumb , if you want to get thumb-2 , you will set 2, default value is 'default' which return 1
 * @param $multi BOOLEAN weather to return ALL thumbnails in array or just single thumb
 * @param $count BOOLEAN just count thumbs or not, if set to true, function will return number of thumb INT only
 * @param $return_full_path BOOLEAN if set to true, thumb will be return along with THUMBS_URL e.g http://cb/thumb/file-1.jpg
 * if set to false, it will return file-1.jpg
 * @param $return_big BOOLEAN weather to return BIG thumbnail or not, if set true, it will return file-big.jpg
 *
 * @since 2.6
 * @param $size STRING dimension of thumb, it can be 120x90, 320x240, it was introduced in 2.6 to get more thumbs
 * using the same funcion.
 * @return STRING video thumbnail with/without path or ARRAY list of video thumbs or INT just number of thumbs
 * 
 */

	 
function get_thumb($vdetails,$num='default',$multi=false,$count=false,$return_full_path=true,$return_big=true,$size=NULL){
	global $db,$Cbucket,$myquery;
	$num = $num ? $num : 'default';
	//checking what kind of input we have
	if(is_array($vdetails))
	{
		if(empty($vdetails['title']))
		{
			//check for videoid
			if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
			{
				if($multi)
					return $dthumb[0] = default_thumb();
				return default_thumb();
			}else{
				if(!empty($vdetails['videoid']))
					$vid = $vdetails['videoid'];
				elseif(!empty($vdetails['vid']))
					$vid = $vdetails['vid'];
				elseif(!empty($vdetails['videokey']))
					$vid = $vdetails['videokey'];
				else
				{
					if($multi)
						return $dthumb[0] = default_thumb();
					return default_thumb();
				}
			}
		}
	}else{
		if(is_numeric($vdetails))
			$vid = $vdetails;
		else
		{
			if($multi)
				return $dthumb[0] = default_thumb();
			return default_thumb();
		}
	}
		
	
	//checking if we have vid , so fetch the details
	if(!empty($vid))
		$vdetails = $myquery->get_video_details($vid);
	
	if(empty($vdetails['title']))
	{
		if($multi)
				return default_thumb();
		return default_thumb();
	}
		
	//Checking if there is any custom function for
	if(count($Cbucket->custom_get_thumb_funcs) > 0)
	{
		
		foreach($Cbucket->custom_get_thumb_funcs as $funcs)
		{
			
			//Merging inputs
			$in_array = array(
			'num' => $num,
			'multi' => $multi,
			'count' => $count,
			'return_full_path' => $return_full_path,
			'return_big' => $return_big
			);
			if(function_exists($funcs))
			{
				$func_returned = $funcs($vdetails,$in_array);
				if($func_returned)
				return $func_returned;
			}
		}
	}
	
	#get all possible thumbs of video
	if($vdetails['file_name'])
	$vid_thumbs = glob(THUMBS_DIR."/".$vdetails['file_name']."*");
	#replace Dir with URL
	if(is_array($vid_thumbs))
	foreach($vid_thumbs as $thumb)
	{
		if(file_exists($thumb) && filesize($thumb)>0)
		{
			$thumb_parts = explode('/',$thumb);
			$thumb_file = $thumb_parts[count($thumb_parts)-1];
			
			if(!is_big($thumb_file) || $return_big)
			{
				if($return_full_path)
					$thumbs[] = THUMBS_URL.'/'.$thumb_file;
				else
					$thumbs[] = $thumb_file;
			}
		}elseif(file_exists($thumb))
			unlink($thumb);
	}
	
	if(count($thumbs)==0)
	{
		if($count)
			return count($thumbs);
		if($multi)
				return $dthumb[0] = default_thumb();
		return default_thumb();
	}
	else
	{
		if($multi)
			return $thumbs;
		if($count)
			return count($thumbs);
		
		//Now checking for thumb
		if($num=='default')
		{
			$num = $vdetails['default_thumb'];
		}
		if($num=='big' || $size=='big')
		{
			
			$num = 'big-'.$vdetails['default_thumb'];
			if(!file_exists(THUMBS_DIR.'/'.$vdetails['file_name'].'-'.$num.'.jpg'))
			$num = 'big';				
		}
		
		$default_thumb = array_find($vdetails['file_name'].'-'.$num,$thumbs);
		
		if(!empty($default_thumb))
			return $default_thumb;
		return $thumbs[0];
	}
	
}


/**
 * Check input file is a big thumb or not
 *
 * @param STRING thumb_file name
 * @return BOOLEAN true|false
 */
 
function is_big($thumb_file)
{
	if(strstr($thumb_file,'big'))
		return true;
	else
		return false;
}



/**
 * function used to get default thumb of ClipBucket 
 *
 * When there is no video thumb, clipbucket will display a processing thumb
 * which can either be located in images folder of ClipBucket selected template
 * or in files/thumbs folder, default image name is always 'processing.jpg' or 'processing.png'
 *
 * @return STRING default thumb with URL
 */
function default_thumb()
{
	//Checking file .png exists in template  or not
	if(file_exists(TEMPLATEDIR.'/images/processing.png'))
	{
		return TEMPLATEURL.'/images/processing.png';
	
	//else try .jpg file
	}elseif(file_exists(TEMPLATEDIR.'/images/processing.jpg'))
	{
		return TEMPLATEURL.'/images/processing.jpg';
	}else
	//else return file from files/thumbs folder
	return BASEURL.'/files/thumbs/processing.jpg';
}


/** 
 * check weather input thumb is 'default' 
 * 
 * @param STRING thumbFile $i
 * @return BOOLEAN 
 */
function is_default_thumb($i)
{
	if(getname($i)=='processing.jpg')
		return true;
	else
		return false;
}

/**
 * Gets link of video
 *
 * Get video link depending how you have configured clipbucket
 * SEO or Non-Seo or change patterns.
 *
 * @param ARRAY video details or it can be INT videoid
 * @param STRING type , {link|download}
 */
function video_link($vdetails,$type=NULL)
{
	global $myquery;
	#checking what kind of input we have
	if(is_array($vdetails))
	{
		if(empty($vdetails['title']))
		{
			#check for videoid
			if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
			{
				return BASEURL;
			}else{
				if(!empty($vdetails['videoid']))
					$vid = $vdetails['videoid'];
				elseif(!empty($vdetails['vid']))
					$vid = $vdetails['vid'];
				elseif(!empty($vdetails['videokey']))
					$vid = $vdetails['videokey'];
				else
					return BASEURL;
			}
		}
	}else{
		if(is_numeric($vdetails))
			$vid = $vdetails;
		else
			return BASEURL;
	}		
	#checking if we have vid , so fetch the details
	if(!empty($vid))
		$vdetails = $myquery->get_video_details($vid);
	
	//calling for custom video link functions
	$functions = cb_get_functions('video_link');
	if($functions)
	{
		foreach($functions as $func)
		{
			$array = array('vdetails'=>$vdetails,'type'=>$type);
			if(function_exists($func['func']))
			{
				$returned = $func['func']($array);
				if($returned)
				{
					$link = $returned;
					return $link;
					break;
				}
			}
		}
	}
	
	$plist = "";
	if(SEO == 'yes'){
		
		if($vdetails['playlist_id'])
			$plist = '?&play_list='.$vdetails['playlist_id'];
			
		switch(config('seo_vido_url'))
		{
			default:
				$link = BASEURL.'/video/'.$vdetails['videokey'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
			break;
			
			case 1:
			{
				$link = BASEURL.'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).'_v'.$vdetails['videoid'].$plist;
			}
			break;
			
			case 2:
			{
				$link = BASEURL.'/video/'.$vdetails['videoid'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
			}
			break;
			
			case 3:
			{
				$link = BASEURL.'/video/'.$vdetails['videoid'].'_'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
			}
			break;
		}

	}else{
		if($vdetails['playlist_id'])
			$plist = '&play_list='.$vdetails['playlist_id'];
		$link = BASEURL.'/watch_video.php?v='.$vdetails['videokey'].$plist;
	}
	if(!$type || $type=='link')
		return $link;
	elseif($type=='download')
		return BASEURL.'/download.php?v='.$vdetails['videokey'];
}


/**
 * get video thumb in smart template
 * 
 * This is an alias of get_thumb() function to get thumb in templates
 * please read our documentation about template functions for more information
 * about {getSmartyThumb|getThumb}
 */
function getSmartyThumb($params)
{
	return get_thumb($params['vdetails'],$params['num'],$params['multi'],$params['count_only'],true,true,$params['size']);
}
