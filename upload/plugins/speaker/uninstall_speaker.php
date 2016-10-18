<?php
require_once('../includes/common.php');

/**
 *Remove speaker table from the database 
 */
function uninstallSpeaker() {
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("speaker").''
	);
}
	
/**
 *Remove speakerfunction table from the database 
 */
function uninstallSpeakerfunction() {
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("speakerfunction").''
	);
}

/**
 *Remove video_speaker table from the database 
 */
function uninstallVideospeaker()
{
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("video_speaker").''
	);
}

uninstallVideospeaker();
uninstallSpeakerfunction();
uninstallSpeaker();

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