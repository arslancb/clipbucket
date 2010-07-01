<?php
/*
Simple Plugin System
@ Author :  Arslan
*/

//Getting Plugin Config Details


$installed_plugins = $cbplugin->getInstalledPlugins();
if(is_array($installed_plugins))
{
	foreach($installed_plugins as $plugin)
	{
		$folder = "";
		if($plugin['folder'])
			$folder = '/'.$plugin['folder'];
		$file = PLUG_DIR.$folder.'/'.$plugin['file'];
		if(file_exists($file))
			include_once($file);
	}
}

/**
 * Include ClipBucket Player
 */

if($Cbucket->configs['player_file'] !='')
{
	if($Cbucket->configs['player_dir'])
		$folder = '/'.$Cbucket->configs['player_dir'] ;
	$file = PLAYER_DIR.$folder.'/'.$Cbucket->configs['player_file'] ;
	if(file_exists($file))
		include_once($file);
}


//include_once(PLAYER_DIR.'/cbplayer/cbplayer.plug.php');



/**
 * Adding Inactive sign on vdeo
 */

function display_inactive_sign($vdo)
{
	if($vdo['active']=='no')
	{
		echo '<div style="position:absolute;top:2px; height:13px; background-color:#ed0000; width:100%; color:#fff; font-size:10px; text-align:center">Video is inactive</div>';
	}
}
register_anchor_function('display_inactive_sign','in_video_thumb');

?>