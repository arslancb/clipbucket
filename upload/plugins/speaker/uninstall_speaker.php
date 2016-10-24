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

/**
 * remove management for this plugin administration permissions
 *
 * Add fields and values in the database to allow the administrator setting on or off the administration
 * part of this plugin
 */
function uninstallSpeakerAdminPermissions(){
	global $db;
	/** Remove the added field into user_level_permission table  that s used tu manage permissions for each user level */
	$db->Execute('ALTER TABLE '.tbl("user_levels_permissions"). " DROP `speaker_admin` ");

	/** Remove the entry into the user_permission table that deal with this adminstration level */
	$db->Execute ("DELETE FROM ".tbl('user_permissions')." WHERE `permission_code` = 'speaker_admin'");
}


uninstallVideospeaker();
uninstallSpeakerfunction();
uninstallSpeaker();
uninstallSpeakerAdminPermissions();

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