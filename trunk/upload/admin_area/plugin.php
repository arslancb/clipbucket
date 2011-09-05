<?php

/**
 * File used to operate plugin files
 * this will include plugin file based on input folder and file
 */
 
require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

$file = get('file');
$folder = get('folder');
$player = get('player');
if($folder && $file)
{
	if(!$player)
	$file = PLUG_DIR.'/'.$folder.'/'.$file;
	else
	$file = PLAYER_DIR.'/'.$folder.'/'.$file;
	
	if(file_exists($file))
	{
		require($file);
		display_it();
		exit();
	}
}

header('location:plugin_manager.php?err=no_file');
?>