<?php
/*
	Player Name: Ulille videojs player
	Description: Video JS Integration for ULILLE
	Author: Franck Rouzé
	ClipBucket Version: 2.8.1
 
* @Author : Franck Rouzé
* @Script : ClipBucket v2
*/

$ulille_player = false;


if (!function_exists('ulille_player'))
{

	define("ULILLE_PLAYER",basename(dirname(__FILE__)));
	define("ULILLE_PLAYER_DIR",PLAYER_DIR."/".ULILLE_PLAYER);
	define("ULILLE_PLAYER_URL",PLAYER_URL."/".ULILLE_PLAYER);
	assign('ulille_player_dir',ULILLE_PLAYER_DIR);
	assign('ulille_player_url',ULILLE_PLAYER_URL);

	function ulille_player($in)
	{
		global $ulille_player;
		$ulille_player = true;
		
		$vdetails = $in['vdetails'];

		$video_play = get_video_files($vdetails,true,true);
	
		vids_assign($video_play);

		if(!strstr($in['width'],"%"))
			$in['width'] = $in['width'].'px';
		if(!strstr($in['height'],"%"))
			$in['height'] = $in['height'].'px';


		assign('height',$in['height']);
		assign('width',$in['width']);
		assign('player_config',$in);
		assign('vdata',$vdetails);
		
//		assign('cb_logo',cb_logo());

		#assign('video_files',$video_play);
		Template(ULILLE_PLAYER_DIR.'/ulille_player.html',false);
		return true;
	}

	
	/*
	* This Function is written to get qulaity of current file
	*/
	function get_ulille_quality($src){
		$quality = explode('-', $src);
		$quality = end($quality);
		$quality = explode('.',$quality);
		$quality = $quality[0];
		return $quality;
	}

	
	
	
	/*
	* This Function is written to set default resolution for cb_vjs_player
	*/
	function get_ulille_quality_type($video_files){
		if ($video_files){
			$one_file = get_ulille_quality($video_files[0]);
			if (is_numeric($one_file)){
				$cb_combo_res = True;
			}else{
				$cb_combo_res = False;
			}

			if ($cb_combo_res){
				foreach ($video_files as $key => $file) {
					$res[] = get_ulille_quality($file);
				}
				$all_res = $res;
				if (in_array('360', $all_res)){
					$quality = '360';
				}
				else{
					$quality = 'low';
				}
			}else{
				$quality = "low";
			}
			return $quality;
		}
		else{
			return False;
		}
		
	}

	
	
	
	
	
	register_actions_play_video('ulille_player');
}