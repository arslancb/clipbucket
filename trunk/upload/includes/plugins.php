<?php
/*
Simple Plugin System
@ Author :  Arslan
*/

//Getting Plugin Config Details
$plug_row = $myquery->Get_Plugin_Details();

if(FRONT_END){
	$installed_plugins = $cbplugin->getInstalledPlugins();
	foreach($installed_plugins as $plugin)
	{
		if($plugin['folder'])
			$folder = '/'.$plugin['folder'];
		$file = PLUG_DIR.$folder.'/'.$plugin['file'];
		if(file_exists($file))
			include_once($file);
	}
}

if(BACK_END)
{
	$plugin_list = $cbplugin->getPluginList();
	foreach($plugin_list as $plugin)
	{
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

if($Cbucket->configs['player_file'] !='cbplayer.plug.php' && $Cbucket->configs['player_file'] !='')
{
	if($Cbucket->configs['player_dir'])
		$folder = '/'.$Cbucket->configs['player_dir'] ;
	$file = PLAYER_DIR.$folder.'/'.$Cbucket->configs['player_file'] ;
	if(file_exists($file))
		include_once($file);
}
include_once(PLAYER_DIR.'/cbplayer/cbplayer.plug.php');

?>