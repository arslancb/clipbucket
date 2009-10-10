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
			if($data['hq'])
				$swfobj->addParam('flashvars',"playlistXML=http://clipbucket.net/v2/player/hd_flv_player/settings.php?hqid=".$vdata['videoid']);
			else
				$swfobj->addParam('flashvars',"playlistXML=http://clipbucket.net/v2/player/hd_flv_player/settings.php?vid=".$vdata['videoid']);

			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	register_actions_play_video('hdflvplayer');
}

?>