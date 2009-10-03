<?php
/*
Player Name: ClipBucket Player
Description: ClipBucket default player - flv,mp4,f4v,mov,h264,red5
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Player type: global
*/

/**
 * ClipBucket v2 Player
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : CBLA
 * @Since : September 15 2009
 */



if(!function_exists('cbplayer'))
{
	
	define("CBV2PLAYER",TRUE);
	define("AUTOPLAY",false);
	
	function cbplayer($data,$no_video=false)
	{
		$vdata = $data['vdetails'];
		global $swfobj;
		
		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
				$code 	 = "var flashvars = {\n";
				$code	.= " htmlPage: document.location,\n";
				if($data['hq'])
				$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/settings.php?hqid=".$vdata['videoid']."\"\n";
				else
				$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/settings.php?vid=".$vdata['videoid']."\"\n";
				$code	.= "};\n";
				$code	.= "var params = {\n";
				$code	.= "  allowFullScreen: \"true\"\n";
				$code	.= "};\n";
				$code	.= "swfobject.embedSWF(\"".PLAYER_URL."/cbplayer/videoPlayer.swf\", 
								   \"".$data['player_div']."\", \"".config('player_width')."\", \"".config('player_height')."\", \"9.0.115\",
								   \"swfobject/expressInstall.swf\", flashvars
								   , params)";
				return $code;
		}else
			return false;
	}
	
	function default_embed_code($vdetails)
	{
		$code = '';
		$code .= '<object width="300" height="250">';
		$code .= '<param name="movie" value="http://www.youtube.com/v/2dEdVwg7to4&hl=en&fs=1&"></param>';
		$code .= '<param name="allowFullScreen" value="true"></param>';
		$code .= '<param name="allowscriptaccess" value="always"></param>';
		$code .= '<embed src="'.PLAYER_URL.'/cbplayer/videoPlayer.swf?settingsFile='.PLAYER_URL.'/cbplayer/settings.php?vid='.$vdetails['videoid'].'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="250"></embed>';
		$code .= '</object>';
		
		return $code;
	}
	
	register_actions_play_video('cbplayer');
}
?>