<?php
/*
	Player Name: cb video js 1.0 BETA
	Description: New Official cb video js player 
	Author: Fahad Abbas
	ClipBucket Version: 2.7

 
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 
 */

$cb_video_js = false;


if (!function_exists('cb_video_js'))
{
	define("CB_VJS_PLAYER",basename(dirname(__FILE__)));
	define("CB_VJS_PLAYER_DIR",PLAYER_DIR."/".CB_VJS_PLAYER);
	define("CB_VJS_PLAYER_URL",PLAYER_URL."/".CB_VJS_PLAYER);
	assign('cb_vjs_player_dir',CB_VJS_PLAYER_DIR);
	assign('cb_vjs_player_url',CB_VJS_PLAYER_URL);

	function cb_video_js($in)
	{
		global $cb_video_js;
		$cb_video_js = true;
		
		$vdetails = $in['vdetails'];

		$video_play = get_video_files($vdetails,true,true);
		
		if(!strstr($in['width'],"%"))
			$in['width'] = $in['width'].'px';
		if(!strstr($in['height'],"%"))
			$in['height'] = $in['height'].'px';


		assign('height',$in['height']);
        assign('width',$in['width']);
		assign('player_config',$in);
		assign('vdata',$vdetails);
		assign('cb_logo',cb_logo());
		assign('video_files',$video_play);
		Template(CB_VJS_PLAYER_DIR.'/cb_video_js.html',false);
		return true;
	}


	function cb_logo()
	{
		$l_details = BASEURL.'/images/icons/country/hp-cb.png';
		$l_convert = base64_encode(file_get_contents($l_details));
		return $l_convert;
	}

	register_actions_play_video('cb_video_js');
}

?>