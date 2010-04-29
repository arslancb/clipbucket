<?php
/*
	Player Name: JW Player Smart
	Description: Jw Player Smart Player fro ClipBucket v2
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0 - JW 5
	Website: http://clip-bucket.com/
*/


/**
 * This file is used to instruct ClipBucket
 * for how to operate JW PLayer
 * from docs.clip-bucket.com
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : CBLA
 * @Since : Febraury 24, 2010
 */


if(!function_exists("jw_smart"))
{
	function jw_smart($data)
	{
		
		$vdata = $data['vdetails'];
		global $swfobj,$cb_jw_smart;
		
		//Getting Video File
		$vid_file = get_video_file($vdata,$no_video,false);
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
			$hd = $data['hq'];
			
			$swfobj->width = $data['width'];
			$swfobj->height = $data['height'];
			$swfobj->playerFile = PLAYER_URL.'/jw_smart/player-viral.swf';
			$swfobj->DivId = $data['player_div'] ? $data['player_div'] : config('player_div_id');
			
			$swfobj->FlashObj();
			//Writing Param
			$swfobj->addParam('allowfullscreen','true');
			$swfobj->addParam('allowscriptaccess','always');
			$swfobj->addParam('wmode','opaque');
			
			if($hd=='yes') $file = get_hq_video_file($vdata); else $file = get_video_file($vdata,true,true);
			$hd_file = get_hq_video_file($vdata);
			
			if($cb_jw_smart)
			{
				
				//Setting Skin
				if($cb_jw_smart->configs['jw_skin'])
					$swfobj->addVar('skin',$cb_jw_smart->skin($cb_jw_smart->configs['jw_skin']));
				//Adding Longtail Add Solution
				if($cb_jw_smart->configs['longtail_enabled']=='yes')
				{
					if(!empty($cb_jw_smart->configs['plugin_var']))
						$cb_jw_smart->configs['plugin_var'] .= ',';
					$cb_jw_smart->configs['plugin_var'] .= 'ltas';
					$swfobj->addVar('ltas.cc',$cb_jw_smart->configs['longtail_id']);
				}
				//Setting Plugin Vars
				if($cb_jw_smart->configs['plugin_var'])
				$swfobj->addVar('plugins',$cb_jw_smart->configs['plugin_var']);
				//Calling For Custom Variables
				$vars = $cb_jw_smart->json_to_custom($cb_jw_smart->configs['custom_variables']);
				if(is_array($vars))
				{
					foreach($vars as $name=>$value)
					{
						//Replacing Some Files
						$vars = array('/video_file/','/hd_file/');
						$cb_vars = array($file,$hd_file);
						
						$swfobj->addVar($name,preg_replace($vars,$cb_vars,$value));
					}
				}
				
				//Setting up the logo
				$swfobj->addVar('logo.file',website_logo());
				$swfobj->addVar('logo.link ',BASEURL);
				$swfobj->addVar('logo.position',$cb_jw_smart->configs['logo_placement']);
			}
			
			if($vid_file)
				$swfobj->addVar('file',$file);
			else
				$swfobj->addVar('file',urlencode($ref));
			
			$swfobj->CreatePlayer();
			return $swfobj->code;
		}else
			return false;
	}
	
	function jw_embed_code($vdetails)
	{
		
		$vid_file = get_video_file($vdetails,false,true);
		//Checking for YT Referal
		$ref = $vdetails['refer_url'];
		//Checking for youtube
		if(function_exists('is_ref_youtube'))
			$ytcom = is_ref_youtube($ref);
		if($ytcom)
			$is_youtube = true;
		else
			$is_youtube = false;
			
		if($vid_file || $is_youtube)
		{
			if(!$vid_file)
				$vid_file = urlencode($ref);
			$code = '';
			$code .= "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='".EMBED_VDO_WIDTH."' height='".EMBED_VDO_HEIGHT."'
			id='cb_embed_player1' name='cb_embed_player1'>";
			$code .= "<param name='movie' value='".PLAYER_URL.'/jw_smart/player-viral.swf'."'>";
			$code .= "<param name='allowfullscreen' value='true'>";
			$code .= "<param name='allowscriptaccess' value='always'>";
			$code .= "<param name='flashvars' value='file=".$vid_file."'>";
			$code .= "<embed id='cb_embed_player1'";
			$code .= "		  name='cb_embed_player1'";
			$code .= "		  src='".PLAYER_URL."/jw_smart/player-viral.swf'";
			$code .= "		  width='".EMBED_VDO_WIDTH."'";
			$code .= "		  height='".EMBED_VDO_HEIGHT."'";
			$code .= "		  allowscriptaccess='always'";
			$code .= "		  allowfullscreen='true'";
			$code .= "		  flashvars='file=".$vid_file."'";
			$code .= "   />";
			$code .= "</object>";
			return $code;
		}else
		{
			return embeded_code($vdetails);
		}
	}
	
	add_js(array('swfobject.obj.js'=>'global'));
	register_actions_play_video('jw_smart');
	register_embed_function('jw_embed_code');
	//Adding Embed Function 
	
}
?>