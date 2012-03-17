<?php

        /**
	 * Function used to display flash player for ClipBucket video
	 */
	function flashPlayer($param)
	{
		global $Cbucket,$swfobj;
		
		$param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';
		
		$key 		= $param['key'];
		$flv 		= $param['flv'].'.flv';
		$code 		= $param['code'];
		$flv_url 	= $file;
		$embed 		= $param['embed'];
		$code 		= $param['code'];
		$height 	= $param['height'] ? $param['height'] : config('player_height');
		$width 		= $param['width'] ? $param['width'] : config('player_width');
		$param['height'] = $height;
		$param['width'] = $width ;
		
		if(!$param['autoplay'])
		$param['autoplay'] = config('autoplay_video');
		
		assign('player_params',$param);
		if(count($Cbucket->actions_play_video)>0)
		{
	 		foreach($Cbucket->actions_play_video as $funcs )
			{
				
				if(function_exists($funcs))
				{
					$func_data = $funcs($param);
				}
				if($func_data)
				{
					$player_code = $func_data;
					break;
				}
			}
		}
		
		if(function_exists('cbplayer') && empty($player_code))
			$player_code = cbplayer($param,true);
		
		global $pak_player;
		
		if($player_code)
		if(!$pak_player && $show_player)
		{
			assign("player_js_code",$player_code);
			Template(PLAYER_DIR.'/player.html',false);
			return false;
		}else
		{
			return false;
		}
		
		return blank_screen($param);
	}
	
	
	/**
	 * FUnctiuon used to plya HQ videos
	 */
	function HQflashPlayer($param)
	{
		return flashPlayer($param);
	}
	
	
	/**
	 * Function used to get player from website settings
	 */
	function get_player()
	{
		global $Cbucket;
		return $Cbucket->configs['player_file'];
	}
        
?>