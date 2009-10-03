<?php

/*
Player Name: HD FLV Player
Description: HDFLV Player from BALA - hdflvplayer.nte
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://hdflvplayer.net/
Player type: global
*/



if(!function_exists('hdflvplayer'))
{
	
	define("HDFLVPLAYER",TRUE);
	define("AUTOPLAY",false);
	
	function hdflvplayer($data,$no_video=false)
	{
		$vdata = $data['vdetails'];
		global $swfobj;
		
		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
			$swfobj->playerFile = PLAYER_URL.'/hd_flv_player/hdplayer.swf';
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');	
			$swfobj->addParam('flashvars','playlistXML=http://localhost/clipbucket/2.x/2/upload/player/hd_flv_player/xml/playlist.xml');
			
			$swfobj->addVar('file',BASEURL.'/files/videos/'.$vid_file);

			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	register_actions_play_video('hdflvplayer');
}

?>