<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Remove links table from the database 
 */
function uninstall_links()	{
		global $db;
		$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("links").''
		);
	}
	
/**
 * Remove video_links table from the database 
 */
function uninstall_video_links()
{
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("video_links").''
	);
}


uninstall_video_links();
uninstall_links();
uninstallPluginAdminPermissions("links");

/**
 * remove locales for this plugin
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	removeLangagePack($folder,'en');
	removeLangagePack($folder,'fr');
}
?>