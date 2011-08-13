<?php

/**
 * Function used to handle players
 */
 
 

class CBPlayer
{
	
	/**
	 * Function used to read player directory and get list of all available players
	 */
	function getPlayers()
	{
		#first we will read the plugin directory
		$dir = PLAYER_DIR;
		$dir_list = scandir($dir);
		foreach($dir_list as $item)
		{
			if($item=='..' || $item=='.' || substr($item,0,1)=='_'|| substr($item,0,1)=='.')
			{
				//Skip $item_list[] = $item;
				//$sub_dir_list = scandir(PLAYER_DIR.'/'.$item);
			}else{
				//Now CHecking if its file, not a directory
				if(!is_dir(PLAYER_DIR.'/'.$item))
				{
					$item_list[] = $item;
				}else{
					$sub_dir = $item;
					$sub_dir_list = scandir(PLAYER_DIR.'/'.$item);
					foreach($sub_dir_list as $item)
					{
						if($item=='..' || $item=='.' || substr($item,0,1)=='_'|| substr($item,0,1)=='.')
						{
							//Skip $item_list[] = $item;
							//$sub_dir_list = scandir(PLUG_DIR.'/'.$item);
						}else{
							//Now CHecking if its file, not a directory
							if(!is_dir(PLAYER_DIR.'/'.$sub_dir.'/'.$item))
							{
								$subitem_list[$sub_dir][] = $item;
							}
						}
					}
				}
			}
		}
		//Our Plugin List has plugin main files only, now star reading files
		foreach($item_list as $player_file)
		{
			$player_details = $this->getPlayerDetails($player_file);
			if(!empty($player_details['name']))
			$player_array[]= $player_details;
		}
		
		//Now Reading Sub Dir Files
		foreach($subitem_list as $sub_dir => $sub_dir_list )
		{
			foreach($subitem_list[$sub_dir] as $player_file)
			{
				$player_details = $this->getPlayerDetails($player_file,$sub_dir);
				$player_details['dir'] = $player_details['folder'] = $sub_dir;
				if(!empty($player_details['name']))
				$player_array[] = $player_details;
			}
		}
		
		return $player_array;
	}
	
	/**
	 * Function used to get ClipBucket Player
	 */
	function get_players()
	{
		return $this->getPlayers();
	}
	
	/**
	 * Function used to get player details
	 * @input = file
	 */
	function get_player_details($player_file,$sub_dir=NULL)
	{
		if($sub_dir!='')
			$sub_dir = $sub_dir.'/';
			
		$file = PLAYER_DIR.'/'.$sub_dir.$player_file;
		if(file_exists($file) && is_file($file))
		{
			// We don't need to write to the file, so just open for reading.
			$fp = fopen($file, 'r');
			// Pull only the first 8kiB of the file in.
			$plugin_data = fread( $fp, 8192 );
			// PHP will close file handle, but we are good citizens.
			fclose($fp);
			preg_match( '/Player Name:(.*)$/mi', $plugin_data, $name );
			preg_match( '/Website:(.*)$/mi', $plugin_data, $website );
			preg_match( '/Version:(.*)/mi', $plugin_data, $version );
			preg_match( '/Description:(.*)$/mi', $plugin_data, $description );
			preg_match( '/Author:(.*)$/mi', $plugin_data, $author );
			preg_match( '/Author Website:(.*)$/mi', $plugin_data, $author_page );
			preg_match( '/ClipBucket Version:(.*)$/mi', $plugin_data, $cbversion );
			preg_match( '/Player Type:(.*)$/mi', $plugin_data, $type );
			preg_match( '/Released:(.*)$/mi', $plugin_data, $released );
			
			$details_array = array
			(
			 'name',
			 'website',
			 'version',
			 'description',
			 'author',
			 'cbversion',
			 'author_page',
			 'code',
			 'type',
			 'released',
			 
			 );
			foreach ($details_array as $detail)
			{
				$plugin_array[$detail]=${$detail}[1];
			}
			$plugin_array['file'] = $player_file;
			$plugin_array['dir'] = $plugin_array['folder'] = $sub_dir;
			$plugin_array['code'] = preg_replace('/\s/', '', $code[1]);
			
			return $plugin_array;
		}else{
			return false;
		}
	}
	function getPlayerDetails($file,$sub_dir=NULL)
	{
		return $this->get_player_details($file,$sub_dir);
	}
	
	
	/**
	 * Function used to get template thumb
	 */
	function get_preview_thumb($player)
	{
		$path = $player.'/preview.';
		$exts = array('png','jpg','gif');
		$thumb_path = BASEURL.'/images/icons/no_thumb_player.png';
		foreach($exts as $ext)
		{
			$file = PLAYER_DIR.'/'.$path.$ext;
			if(file_exists($file))
			{
				$thumb_path = PLAYER_URL.'/'.$path.$ext;
				break;
			}
		}
		return $thumb_path;		
	}
	
	/**
	 * Function used to set player for ClipBucket
	 */
	function set_player($details)
	{
		global $myquery;
		
		if($this->getPlayerDetails($details['file'],$details['folder']))
		{
			$myquery->Set_Website_Details('player_file',$details['file']);
			$myquery->Set_Website_Details('player_dir',$details['folder']);
			e(lang("player_activated"),'m');
		}else
			e(lang("error_occured_while_activating_player"));
	}
	function setplayer($details)
	{
		return $this->set_player($details);
	}
	
}
	
	/**
	 * this is for pak player
	 */
	 
	function fb_embed_video($params)
	{
		$fb_embed_funcs = cb_get_functions('fb_embed_video');
		if($fb_embed_funcs)
		{
			foreach($fb_embed_funcs as $func)
			{
				if(function_exists($func['func']))
				{
					return $func['func']($params);
				}
			}
		}
		
		$vdetails = $params['video'];
		$config  = urlencode(BASEURL."/player/pak_player/embed_player.php?vid=".$vdetails['videoid']."&json=true&autoplay=yes");
		if(!config('pak_license'))
			$embed_src = BASEURL.'/player/pak_player/pakplayer.swf?config='.$config;
		else
			$embed_src = BASEURL.'/player/pak_player/pakplayer.unlimited.swf?config='.$config;
		
		return $embed_src;
	}

?>