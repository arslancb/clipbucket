<?php
/*
	Player Name: JW Player Plugin
	Description: jw Player for Clipbucket
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0 - JW 4.5.230
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


if(!function_exists(jw_player))
{
	function jw_player($vdata)
	{
		
		$vdata = $data['vdetails'];
		global $swfobj;
		
		$vid_file = get_video_file($vdata,$no_video,false);
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
		}else
			return false;
	}
	
	add_js(array('swfobject.obj.js'=>'global'));
	register_actions_play_video('jw_player');
}
?>