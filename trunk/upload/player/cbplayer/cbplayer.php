<?php
/*
	Player Name: ClipBucket (CB) Player 1.0
	Description: Official ClipBucket Player with all required features
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/cb-player

 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 *
 * CBPlayer or ClipBucket player is based on JW Player source, anything that works
 * with JWPlayer will ultimately work with Pakplayer
 * CBPlayer license comes under AAL(OSI) please read our license agreement carefully
 */

$cb_player = false;

if(!function_exists('cb_player'))
{
	define("CB_PLAYER",basename(dirname(__FILE__)));
	define("CB_PLAYER_DIR",PLAYER_DIR."/".CB_PLAYER);
	define("CB_PLAYER_URL",PLAYER_URL."/".CB_PLAYER);
	assign('cb_player_dir',CB_PLAYER_DIR);
	assign('cb_player_url',CB_PLAYER_URL);
	
	function cb_player($in)
	{
		global $cb_player;
		$cb_player = true;
		
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
			
			if(!strstr($in['width'],"%"))
				$in['width'] = $in['width'].'px';
			if(!strstr($in['height'],"%"))
				$in['height'] = $in['height'].'px';
		
			if($in['autoplay'] =='yes' || $in['autoplay']===true || 
			($_COOKIE['auto_play_playlist'] && ($_GET['play_list'] || $_GET['playlist'])))
			{
				$in['autoplay'] = true;
			}else{
				$in['autoplay'] = false;
			}
			
			
			//Logo Placement
			assign('logo_placement',cb_player_logo_position());
			assign('logo_margin',config('logo_padding'));
			
			//Setting Skin
			assign('cb_skin','glow/glow.xml');
			
			assign('player_data',$in);
			assign('player_logo',website_logo());
			assign('normal_vid_file',$vid_file);
			assign("hq_vid_file",$hd_file);			
			assign('vdata',$vdetails);
			Template(CB_PLAYER_DIR.'/cbplayer.html',false);
			
			return true;
		}
	}
	
	/**
	 * Setting logo position for CB Player
	 */
	function cb_player_logo_position($pos=false)
	{
		if(!$pos)
			$pos = config('logo_placement');
		switch($pos)
		{
			case "tl":
			$position = "top-left";
			break;
			
			case "tr":
			$position = "top-right";
			break;
			
			case "br":
			$position = "bottom-right";
			break;
			
			case "bl":
			$position = "bottom-left";
			break;
			
		}
		
		return $position;
	}
	
	/**
	 * This function generates src for embedable link
	 * which can be used in OBJECT tag to embed videos on a website
	 *
	 * @param video details
	 * @return src link
	 */
	function cbplayer_embed_src($vdetails)
	{	
		$config  = urlencode(BASEURL."/player/".CB_PLAYER."/embed_player.php?vid="
		.$vdetails['videoid']."&autoplay=".config('autoplay_embed'));
		
		$embed_src = BASEURL.'/player/'.CB_PLAYER.'/player.swf?config='.$config;

		
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
	
	/**
	 * Writing CB Player function to play videos on facebook
	 */
	function cb_facebook_embed($params)
	{
		$vdetails = $params['video'];
		$config  = urlencode(BASEURL."/player/".CB_PLAYER."/embed_player.php?vid="
		.$vdetails['videoid']."&autoplay=".config('autoplay_embed'));
		$embed_src = BASEURL.'/player/'.CB_PLAYER.'/player.swf?config='.$config;	
		return $embed_src;
	}
		
	
	register_embed_function('cbplayer_embed_src');
	register_actions_play_video('cb_player');
	cb_register_function('cb_facebook_embed','fb_embed_video');
	//include Pak Player JS File
	$Cbucket->add_header(CB_PLAYER_DIR.'/cbplayer_header.html');
	$Cbucket->add_admin_header(CB_PLAYER_DIR.'/cbplayer_header.html');
	
	/**
	 * Including plugin files 
	 */
	include("cbplayer.plugin.php");
}
?>