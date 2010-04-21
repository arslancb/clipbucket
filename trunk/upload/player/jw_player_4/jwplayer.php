<?php
/*
	Player Name: JW Player 4.x
	Description: JW Player 4.x Plugin for Clipbucket
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
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 */


if(!function_exists('jw_player'))
{
	function jw_player($data)
	{
		
		$vdata = $data['vdetails'];
		global $swfobj;
		
		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
			$hd = $data['hq'];
			
			$swfobj->width = $data['width'];
			$swfobj->height = $data['height'];
			$swfobj->playerFile = PLAYER_URL.'/jw_player_4/player.swf';
			$swfobj->DivId = $data['player_div'] ? $data['player_div'] : config('player_div_id');
			
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			$swfobj->addParam('wmode','opaque');
			
			if($hd=='yes') $file = get_hq_video_file($vdata); else $file = get_video_file($vdata,true,true);
			
			$swfobj->addVar('file',$file);
			
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	add_js(array('swfobject.obj.js'=>'global'));
	register_actions_play_video('jw_player');
}
?>