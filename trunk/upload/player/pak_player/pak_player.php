<?php
/*
	Player Name: Pak Player 1.0
	Description: Official ClipBucket Player with all required features
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/pak-player

 * This file is used to instruct ClipBucket
 * for how to operate Flow player
 * from docs.clip-bucket.com
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 *
 * Pakplayer is originated from Flowplayer so all things that works with flowplayer
 * will ultimately work with Pakplayer
 * Pakplayer license comes under AAL(OSI) please read our license agreement carefully
 */

$pak_player = false;
if(!function_exists("pak_player"))
{
	$cbvid->embed_src_func_list[] = "pak_embed";
	
	
	
	define("PAK_PLAYER_DIR",PLAYER_DIR."/pak_player");
	define("PAK_PLAYER_URL",PLAYER_URL."/pak_player");
	assign('pak_player_dir',PAK_PLAYER_DIR);
	assign('pak_player_url',PAK_PLAYER_URL);


	function pak_embed($vid){ 
	header('Content-Type: application/x-shockwave-flash'); 
	echo file_get_contents(PAK_PLAYER_DIR.'/pakplayer.swf');
	 exit(); }
	
	/**
	 * this function will play pak player
	 * @param : $in ARRAY 
	 * array('vdetails' [all video details])
	 */
	function pak_player($in)
	{
		global $pak_player;
		$pak_player = true;
		
		$vdetails = $in['vdetails'];
		$vid_file = get_video_file($vdetails,true,true);
		//Checking for YT Referal
	

		if(function_exists('get_refer_url_from_embed_code'))
		{
			$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
			$ytcode = $ref_details['ytcode'];
		}
		
		if($vid_file || $ytcode)
		{
			$hd = $data['hq'];
			
			if($hd=='yes') $file = get_hq_video_file($vdetails); else $file = get_video_file($vdetails,true,true);
			$hd_file = get_hq_video_file($vdetails);
			
			
			if($ytcode)
			{
				assign('youtube',true);
				assign('ytcode',$ytcode);
			}
			
			if(!strstr($in['width'],"\%"))
				$in['width'] = $in['width'].'px';
			if(!strstr($in['height'],"\%"))
				$in['height'] = $in['height'].'px';
		
			if($in['autoplay'] =='yes' || $in['autoplay']===true || ($_COOKIE['auto_play_playlist'] && $_GET['playlist']))
			{
				$in['autoplay'] = true;
			}else{
				$in['autoplay'] = false;
			}
			
			
			//Logo Placement
			$placement = config('logo_placement');
			switch($placement)
			{
				case "tl":
				assign('logo_top','5');
				assign('logo_left','5');
				assign('logo_position','top:5,left:5');
				break;
				
				case "tr":
				assign('logo_top','5');
				assign('logo_right','5');
				assign('logo_position','top:5,right:5');
				break;
				
				case "br":
				assign('logo_bottom','5');
				assign('logo_right','5');
				assign('logo_position','bottom:5,right:5');
				break;
				
				case "bl":
				assign('logo_bottom','5');
				assign('logo_left','5');
				assign('logo_position','bottom:5,left:5');
				break;
				
			}

			assign('player_data',$in);
			assign('player_logo',website_logo());
			assign('normal_vid_file',$vid_file);
			assign("hq_vid_file",$hd_file);			
			assign('vdata',$vdetails);
			assign('pakplayer','yes');
			assign('updateEmbedCode','updateEmbedCode');
			Template(PAK_PLAYER_DIR.'/player.html',false);
			
			return true;
		}
	}
	
	/**
	 * This function generates src for embedable link
	 * which can be used in OBJECT tag to embed videos on a website
	 *
	 * @param video details
	 * @return src link
	 */
	function pakplayer_embed_src($vdetails)
	{	
		$config  = urlencode(BASEURL."/player/pak_player/embed_player.php?vid=".$vdetails['videoid']."&json=true&autoplay=".config('autoplay_embed'));
		if(!config('pak_license'))
			$embed_src = BASEURL.'/player/pak_player/pakplayer.swf?config='.$config;
		else
			$embed_src = BASEURL.'/player/pak_player/pakplayer.unlimited.swf?config='.$config;
		
		if(function_exists('get_refer_url_from_embed_code'))
		{
			$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
			$ytcode = $ref_details['ytcode'];
		}
		
		if(!$vdetails['embed_code']  || $vdetails['embed_code'] =='none'|| $ytcode)
		{
			$code = '<embed src="'.$embed_src.'" type="application/x-shockwave-flash"';
			$code .= 'allowscriptaccess="always" allowfullscreen="true"  ';
			$code .= 'width="'.config("embed_player_width").'" height="'.config("embed_player_height").'"></embed>';
			return $code;
		}else
			return false;
	}
			
	register_embed_function('pakplayer_embed_src');
	register_actions_play_video('pak_player');
	//include Pak Player JS File
	$Cbucket->add_header(PAK_PLAYER_DIR.'/pplayer_head.html');
	$Cbucket->add_admin_header(PAK_PLAYER_DIR.'/pplayer_head.html');
}


?>