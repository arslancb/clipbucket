<?php
/*
	Player Name: Pak Player 1.0
	Description: Official ClipBucket Player with all required features
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/pak-player

 * This file is used to instruct ClipBucket
 * for how to operate Flow player
 * from docs.clip-bucket.com
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 */

$pak_player = false;
if(!function_exists("pak_player"))
{
	define("PAK_PLAYER_DIR",PLAYER_DIR."/pak_player");
	define("PAK_PLAYER_URL",PLAYER_URL."/pak_player");
	assign('pak_player_dir',PAK_PLAYER_DIR);
	assign('pak_player_url',PAK_PLAYER_URL);

	/**
	 * this function will play pak player
	 * @param : $in ARRAY 
	 * array('vdetails' [all video details])
	 */
	function pak_player($in)
	{
		global $pak_player;
		$pak_player = true;
		
		$vdetails = $in['vdetails'];
		$vid_file = get_video_file($vdetails,true,true);
		
		assign('player_logo',website_logo());
		assign('normal_vid_file',$vid_file);
		assign("hq_vid_file",$hq_file);			
		assign('vdata',$vdata);
		Template(PAK_PLAYER_DIR.'/player.html',false);
		
		return true;
	}
	
	register_actions_play_video('pak_player');
	//include Pak Player JS File
	$Cbucket->add_header(PAK_PLAYER_DIR.'/pplayer_head.html');
}


?>