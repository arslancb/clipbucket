<?php
/*
	Player Name: JW Player 5.x
	Description: JW Player 5.x Plugin for Clipbucket 2.x
	Author: Frank White
    Author Website: http://clipbucketmods.com
	ClipBucket Version: 2.x
	Version: 1.0
    Released: 21-04-2010
	Website: http://clipbucketmods.com

#########################################################################################################
# Copyright (c) 2010 ClipBucketMods.com. All Rights Reserved.
# URL:              [url]http://clipbucketmods.com[/url]
# Function:         JW Player 5.x Integration
# Author:           fwhite
# Language:         PHP
# License:          Attribution Assurance License
# License:          http://www.opensource.org/licenses/attribution.php
# ----------------- THIS IS FREE SOFTWARE ----------------
# Version:          $Id$
# Created:          Wednesday, April 21, 2010 / 12:04 PM GMT+1 (fwhite)
# Last Modified:    $Date$
# Notice:           Please maintain this section
#########################################################################################################
*/


if(!function_exists('jw_player5'))
{
	function jw_player5($data)
	{
		$vdata = $data['vdetails'];
		global $swfobj;

		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
			$hd = $data['hq'];
			
			$swfobj->width = $data['width'];
			$swfobj->height = $data['height'];
			$swfobj->playerFile = PLAYER_URL.'/jw_player_5/player.swf';
			$swfobj->DivId = $data['player_div'] ? $data['player_div'] : config('player_div_id');
			
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			$swfobj->addParam('wmode','opaque');
            $swfobj->addVar('stretching','none');            
            $swfobj->addVar('image',getthumb($vdata,'big'));
            //$swfobj->addVar('plugins', PLAYER_URL.'/jw_player_5/plugins/hd.swf');
            $swfobj->addVar('autostart','true');

            $sd_file    = get_video_file($vdata,true,true);
            $hd_file    = get_hq_video_file($vdata);

            if(strlen($hd_file))
            {
                //$swfobj->addVar('hd.file', $hd_file);
            }

            if($hd == 'yes')
            {
                $file = $hd_file;
                //$swfobj->addVar('hd.state', true);
            }
            else
            {
                $file = $sd_file;
                //$swfobj->addVar('hd.state', false);
            }

            $swfobj->addVar('file',$file);
			
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	add_js(array('swfobject.obj.js'=>'global'));
	register_actions_play_video('jw_player5');
}

?>