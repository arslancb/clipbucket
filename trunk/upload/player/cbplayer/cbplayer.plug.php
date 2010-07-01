<?php
/*
Player Name: ClipBucket Player
Description: ClipBucket default player - flv,mp4,f4v,mov,h264,red5
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Player type: global
Released: 25-12-2009
*/

/**
 * ClipBucket v2 Player
 *
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 */



if(!function_exists('cbplayer'))
{
	$Cbucket->addJS(array('swfobject.js'=> 'global'));

	define("CBV2PLAYER",TRUE);
	define("AUTOPLAY",false);
	
	function cbplayer($data,$no_video=false)
	{
		$vdata = $data['vdetails'];
		global $swfobj;
		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
				$code 	 = "var flashvars = {\n";
				$code	.= " htmlPage: document.location,\n";
				if($data['hq'])
				$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/settings.php?hqid=".$vdata['videoid']."&autoplay=".$data['autoplay']."\"\n";
				else
				$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/settings.php?vid=".$vdata['videoid']."&autoplay=".$data['autoplay']."\"\n";
				$code	.= "};\n";
				$code	.= "var params = {\n";
				$code	.= "  allowFullScreen: \"true\"\n";
				$code	.= "};\n";
				$code	.= "swfobject.embedSWF(\"".PLAYER_URL."/cbplayer/videoPlayer.swf\", 
								   \"".$data['player_div']."\", \"".$data['width']."\", \"".$data['height']."\", \"9.0.115\",
								   \"swfobject/expressInstall.swf\", flashvars
								   , params)";
				return $code;
		}else
			return false;
	}
	
	function default_embed_code($vdetails)
	{
		
		$vid_file = get_video_file($vdata,$no_video,false);
		if($vid_file)
		{
		$code = '';
		$code .= '<object width="'.EMBED_VDO_WIDTH.'" height="'.EMBED_VDO_HEIGHT.'">';
		$code .= '<param name="movie" value="'.PLAYER_URL.'/cbplayer/videoPlayer.swf?settingsFile='.PLAYER_URL.'/cbplayer/settings.php?vid='.$vdetails['videoid'].'"></param>';
		$code .= '<param name="allowFullScreen" value="true"></param>';
		$code .= '<param name="allowscriptaccess" value="always"></param>';
		$code .= '<embed src="'.PLAYER_URL.'/cbplayer/videoPlayer.swf?settingsFile='.PLAYER_URL.'/cbplayer/settings.php?vid='.$vdetails['videoid'].'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="250"></embed>';
		$code .= '</object>';
		}else
		{
			return embeded_code($vdetails);
		}
		
		return $code;
	}
	
	/**
	 * Loading Editor's pick videos
	 */
	function show_editor_pick_2($data=NULL)
	{
		$code	= '<div id="show_editor_pick_2">';
		$code	.= '<script type="text/javascript">'."\n";
		$code 	.= "var flashvars = {\n";
		$code	.= " htmlPage: document.location,\n";
		$code	.= "settingsFile: \"".PLAYER_URL."/cbplayer/editor_pick_settings.php\"\n";
		$code	.= "};\n";
		$code	.= "var params = {\n";
		$code	.= "  allowFullScreen: \"true\"\n";
		$code	.= "};\n";
		$code	.= "swfobject.embedSWF(\"".PLAYER_URL."/cbplayer/videoPlayer.swf\", 
						   \"show_editor_pick_2\", \"650\", \"300\", \"9.0.115\",
						   \"swfobject/expressInstall.swf\", flashvars
						   , params)";
		$code	.= "</script>";
		$code	.= "</div>";
		echo $code;
	}
	
	
	register_actions_play_video('cbplayer');
	/**
	 * Works on if Editors pick plugin is installed
	 */
	register_anchor_function(array('show_editor_pick_2'=>'show_editor_pick_2'));
}
?>