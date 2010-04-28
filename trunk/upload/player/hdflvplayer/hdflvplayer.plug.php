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
	define("HD_FLV_PLAYLIST",PLAYER_URL.'/hdflvplayer/playlist.php');
	function hdflvplayer($data,$no_video=false)
	{
		$vdata = $data['vdetails'];
		global $swfobj,$cb_hd_smart;
		
		
		$vid_file = get_video_file($vdata,$no_video,true);
		
		//Checking for YT Referal
		$ref = $vdata['refer_url'];
		
		
		//Checking for youtube
		if(function_exists('is_ref_youtube'))
			$ytcom = is_ref_youtube($ref);
		if($ytcom)
			$is_youtube = true;
		else
			$is_youtube = false;
			
			
		if($vid_file || $is_youtube)
		{
			$hd_file = get_hq_video_file($vdata);
			
			$swfobj->width = $data['width'];
			$swfobj->height = $data['height'];
			$swfobj->playerFile = PLAYER_URL.'/hdflvplayer/hdplayer.swf';
			$swfobj->FlashObj();
			//Writing Param
			
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			
			if($vid_file)
			{
				$swfobj->addVar('file',$vid_file);
				if($hd_file)
					$swfobj->addVar('hdpath',$hd_file);
			}else
				$swfobj->addVar('file',urlencode($ref));
			
			//Now lets setup Playlist
			//First thing first, if there is no playlist, shut it up so wont bother you
			if(!$_GET['play_list'])
			{
				$swfobj->addVar('showPlaylist','false');
				//Setting Autoplay
				$swfobj->addVar('autoplay',$cb_hd_smart->configs['auto_play']);
			}else
			{
				$swfobj->addVar('showPlaylist','true');
				$swfobj->addVar('playlistXML',HD_FLV_PLAYLIST.'?playlist='.mysql_clean($_GET['play_list'].'_'.$vdata['videoid']));
				
				$swfobj->addVar('playlist_autoplay',mysql_clean($_COOKIE['auto_play_playlist']));
				//Setting Autoplay
				$swfobj->addVar('autoplay',"true");
			}
			
			//Setting Logo Position
			$swfobj->addVar('logoalign',$cb_hd_smart->configs['logo_placement']);
			
			//Setting License settings
			if($cb_hd_smart->configs['license'])
			{
				$swfobj->addVar('license',$cb_hd_smart->configs['license']);
			}
			
			if($cb_hd_smart->configs['hd_skin'])
					$swfobj->addVar('skin',$cb_hd_smart->skin($cb_hd_smart->configs['hd_skin']));
					
			//Setting Allow Embed option
			$swfobj->addVar('embed_visible',$cb_hd_smart->configs['embed_visible']);
			
			//Setting Preview Image
			$swfobj->addVar('preview',getThumb($vdata,'big'));
			$swfobj->addVar('skin_autohide',true);
			//Setting Buffer Time
			$buffer = config('buffer_time');
			
			if($buffer<1)
				$buffer = 3;
			if($buffer>10)
				$buffer = 10;
			
			$swfobj->addVar('buffer',$buffer);
			
			$swfobj->addVar('title',"Test");
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	register_actions_play_video('hdflvplayer');
}

?>