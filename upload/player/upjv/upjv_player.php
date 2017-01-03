<?php
/*
	Player Name: VideoJS
	Description: Video JS Integration for UPJV
	Author: Adrien Ponchelet
	ClipBucket Version: 2.8.1
 
* @Author : Adrien Ponchelet
* @Script : ClipBucket v2
*/

$upjv_player = false;


if (!function_exists('upjv_player'))
{

	define("UPJV_PLAYER",basename(dirname(__FILE__)));
	define("UPJV_PLAYER_DIR",PLAYER_DIR."/".UPJV_PLAYER);
	define("UPJV_PLAYER_URL",PLAYER_URL."/".UPJV_PLAYER);
	assign('upjv_player_dir',UPJV_PLAYER_DIR);
	assign('upjv_player_url',UPJV_PLAYER_URL);

	function upjv_player($in)
	{
		global $upjv_player;
		$upjv_player = true;
		
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
		Template(UPJV_PLAYER_DIR.'/upjv_player.html',false);
		return true;
	}

	
	/*
	* This Function is written to get qulaity of current file
	*/
	function get_upjv_quality($src){
		$quality = explode('-', $src);
		$quality = end($quality);
		$quality = explode('.',$quality);
		$quality = $quality[0];
		return $quality;
	}

	
	
	
	/*
	* This Function is written to set default resolution for cb_vjs_player
	*/
	function get_upjv_quality_type($video_files){
		if ($video_files){
			$one_file = get_upjv_quality($video_files[0]);
			if (is_numeric($one_file)){
				$cb_combo_res = True;
			}else{
				$cb_combo_res = False;
			}

			if ($cb_combo_res){
				foreach ($video_files as $key => $file) {
					$res[] = get_upjv_quality($file);
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

	
	
	
	
	
	register_actions_play_video('upjv_player');
}