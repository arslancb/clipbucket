<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

function uninstall_importCSV()	{
	$uploaddir = BASEDIR."/files/importCSV";
	$files = glob($uploaddir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
			unlink($file); // delete file
	}
	rmdir($uploaddir);
	global $db;
	$db->Execute('DROP TABLE  IF EXISTS '.tbl("importCSV_mapping").''	);
	$db->Execute('DROP TABLE  IF EXISTS '.tbl("importCSV_join").''	);
}
	

uninstall_importCSV();
uninstallPluginAdminPermissions("importCSV");

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