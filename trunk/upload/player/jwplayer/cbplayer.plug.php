<?php

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


if(!function_exists('jwplayer'))
{
	
	define("JWPLAYER",TRUE);
	define("AUTOPLAY",false);
	define("SKIN","default.swf");
	
	function jwplayer($vdata)
	{
		global $swfobj;
		$vid_file = get_video_file($vdata,false,false);
		
		if($vid_file)
		{
			$swfobj->playerFile = PLAYER_URL.'/jwplayer/player.swf';
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			$swfobj->addParam('wmode','opaque');
			
			$swfobj->addVar('file',BASEURL.'/files/videos/'.$vid_file);
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else{
			return false;
		}
	}
	
	register_actions_play_video('jwplayer');
}
?>