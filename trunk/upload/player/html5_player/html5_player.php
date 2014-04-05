<?php
/*
	Player Name: Html5_videoplayer 1.0
	Description: New html5 ClipBucket Player with all required features
	Author: Arslan Hassan
	ClipBucket Version: 2.7
	
	
 
 
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 
 */

$html5_player = false;

if(!function_exists('html5_player'))
{
	define("HTML5_PLAYER",basename(dirname(__FILE__)));
	define("HTML5_PLAYER_DIR",PLAYER_DIR."/".HTML5_PLAYER);
	define("HTML5_PLAYER_URL",PLAYER_URL."/".HTML5_PLAYER);
	assign('html5_player_dir',HTML5_PLAYER_DIR);
	assign('html5_player_url',HTML5_PLAYER_URL);
	
	function html5_player($in)
	{
		global $html5_player;
		$html5_player = true;
		
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
			//assign('logo_placement',cb_player_logo_position());
			//assign('logo_margin',config('logo_padding'));
			
			//Setting Skin
			//assign('cb_skin','glow/glow.xml');
			
			$v_details = extract($vdetails);
		    $v_details;
            assign('v_details',$v_details);
            $jquery = BASEDIR.'/js/jquery.js';
            assign('jquery',$jquery);

          
            assign('username',$username);
            assign('title',$title);
            assign('thumb',$default_thumb);

			assign('player_data',$in);
			assign('player_logo',website_logo());
			assign('normal_vid_file',$vid_file);
			assign('hq_vid_file',$hd_file);			
			assign('vdata',$vdetails);
			Template(HTML5_PLAYER_DIR.'/html5_player.html',false);
			
			return true;
		}
	}

/*


	function html5player_embed_src($vdetails)
	{	
		$config  = urlencode(BASEURL."/player/".HTML5_PLAYER."/embed_player.php?vid="
		.$vdetails['videoid']."&autoplay=".config('autoplay_embed'));
		
		$embed_src = BASEURL.'/player/'.HTML5_PLAYER.'/player.swf?config='.$config;

		
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
	
   
*/


   
	register_actions_play_video('html5_player');

	
}




 

?>