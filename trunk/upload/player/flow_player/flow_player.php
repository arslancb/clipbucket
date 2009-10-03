<?php

/*
	Player Name: Flow Player
	Description: Flow Player for Clipbucket
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0 - FlowPlayer 3.1.2
	Website: http://clip-bucket.com/
*/


/**
 * This file is used to instruct ClipBucket
 * for how to operate JW PLayer
 * this file is protype
 * you can get complete instructions
 * from docs.clip-bucket.com
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : CBLA
 * @Since : September 15 2009
 */


if(!function_exists('flowplayer'))
{
	
	define("FLOWPLAYER",TRUE);
	define("AUTOPLAY",false);
	define("SKIN","default.swf");
	
	function flowplayer($vdata)
	{
		$vid_file = get_video_file($vdata,false,false);
		$code = '';
		if($vid_file)
		{
			$code .= "swfobject.embedSWF(\"".PLAYER_URL.'/flow_player/flowplayer.swf'."\", \"videoPlayer\", \"500\", \"320\", \"9.0.0\", null, {  \n";
			$code .= "config: \"{'clip': '".BASEURL.'/files/videos/'.$vid_file."' }}\"\n" ;
			$code .= "} \n";
			$code .= ");  \n"; 
			return $code;
		}else{
			return false;
		}
	}
	register_actions_play_video('flowplayer');
}
?>