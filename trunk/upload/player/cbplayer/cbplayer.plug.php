<?php
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
	
	function cbplayer($vdata)
	{
		global $swfobj;
		
		$vid_file = get_video_file($vdata,false,false);
		if($vid_file)
		{
				$code 	 = "var flashvars = {\n";
				$code	.= " htmlPage: document.location,\n";
				$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/settings.php?vid=".$vdata['videoid']."\"\n";
				$code	.= "};\n";
				$code	.= "var params = {\n";
				$code	.= "  allowFullScreen: \"true\"\n";
				$code	.= "};\n";
				$code	.= "swfobject.embedSWF(\"".PLAYER_URL."/cbplayer/videoPlayer.swf\", 
								   \"videoPlayer\", \"600\", \"350\", \"9.0.115\",
								   \"swfobject/expressInstall.swf\", flashvars
								   , params)";
				return $code;
		}else{
			return false;
		}
	}
	
	register_actions_play_video('cbplayer');
}
?>