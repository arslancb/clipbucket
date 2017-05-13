<?php
//Functions used to uninstall Plugin

/**
 *Remove the plugin tables from the database 
 */
function uninstallTables(){
	global $db;
	
	// remove video_grouping table
	$db->Execute("DROP TABLE ".tbl('video_grouping'));
	// remove vdogrouping table
	$db->Execute("DROP TABLE ".tbl('vdogrouping'));
	// remove vdogrouping_type table
	$db->Execute("DROP TABLE ".tbl('vdogrouping_type'));
	}
/**
 * Remove Thumbnails used by this plugin
 */
function removeVideoGroupingFiles(){
	global $db;
	$uploaddir = BASEDIR."/files/thumbs/video_grouping";
	// remove all thumb images
	$disc = $db->_select("SELECT thumb_url FROM ".tbl("vdogrouping"));
	foreach($disc as $tmp){
		unlink($uploaddir."/".$tmp['thumb_url']);
	}
	unset($tmp);
	$files = glob($uploaddir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
			unlink($file); // delete file
	}
	rmdir($uploaddir);

}



removeVideoGroupingFiles();
uninstallTables();
uninstallPluginAdminPermissions("videogrouping");


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