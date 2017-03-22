<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Remove folder created for video_extension plugin 
 * @deprecated
 */
function uninstallVideoExtensions() {
	/** Remove files and folders stored in the file system */
	$folder = BASEDIR."/files/pending_videos";
	/*$files = glob($uploaddir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
			unlink($file); // delete file
	}
	rmdir($uploaddir);*/
	
	$dirIterator = new RecursiveDirectoryIterator($folder);
	$iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
	foreach($iterator as $fileentry){
		$fileentry->isDir() ? rmdir($fileentry) : unlink($fileentry);
	}
	rmdir($folder);
}

/**
 *Remove external encoding job table from the database
 */
function uninstallJobTable() {
	global $db;
	$db->Execute(
			'DROP TABLE  IF EXISTS '.tbl("job").''
			);
}

/**
 *Remove external encoding job type table from the database
 */
function uninstallJobTypeTable() {
	global $db;
	$db->Execute(
			'DROP TABLE  IF EXISTS '.tbl("job_type").''
			);
}

/**
 *Remove external encoding job encoder table from the database
 */
function uninstallJobEncoderTable() {
	global $db;
	$db->Execute(
			'DROP TABLE  IF EXISTS '.tbl("job_encoder").''
			);
}

/**
 *Remove the field original_videoname from the video table
 */
function removeOriginalVideoname() {
	global $db;
	$db->Execute("ALTER TABLE ".tbl('video')." DROP `original_videoname`");
}

//uninstallVideoExtensions();
uninstallJobTable();
uninstallJobTypeTable();
uninstallJobEncoderTable();
uninstallPluginAdminPermissions("video_extensions");
removeOriginalVideoname();

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