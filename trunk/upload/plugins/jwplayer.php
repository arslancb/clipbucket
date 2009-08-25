<?php
/*
	Plugin Name: JW Player Plugin
	Description: Adds Jw Player for Clipbucket
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0 - JW 4.5.230
	Website: http://clip-bucket.com/
*/


if(!function_exists(jw_player))
{
	function jw_player($vdata)
	{
		global $swfobj;
		if(file_exists(BASEDIR.'/files/videos/'.$vdata['file_name'].'.flv'))
		{
			$swfobj->playerFile = PLUG_URL.'/jw_player/player.swf';
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			$swfobj->addParam('wmode','opaque');
			
			$swfobj->addVar('file',BASEURL.'/files/videos/'.$vdata['file_name'].'.flv');
			
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else{
			return false;
		}
	}
	
	register_actions_play_video('jw_player');
}
?>