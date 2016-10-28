<?php
//Function used to uninstall Plugin
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

$uploaddir = BASEDIR."/files/thumbs/disciplines";
if (!is_dir($uploaddir)) {
	die($uploaddir.lang("not_a_valid_folder"));
}

/**
 *Remove discplines table from the database 
 */
function uninstallDisciplines(){
	global $db;
	$uploaddir = BASEDIR."/files/thumbs/disciplines";
	// remove all thumb images
	$disc = $db->_select("SELECT thumb_url FROM ".tbl("disciplines")." ORDER BY discipline_order ASC");
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

 	// Delete folder recursively 
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}
	rmdir($dir);

	// remove disciplines table
	$db->Execute("DROP TABLE ".tbl('disciplines'));
	// remove discipline field in video table
	$db->Execute("ALTER TABLE ".tbl('video')." DROP `discipline` ");
}



uninstallDisciplines();
uninstallPluginAdminPermissions("discipline");

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