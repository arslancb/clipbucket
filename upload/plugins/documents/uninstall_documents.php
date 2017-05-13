<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Remove documents table from the database 
 */
function uninstallDocuments() {
	/** Remove files and folders stored in the file system */
	$uploaddir = BASEDIR."/files/documents";
	$files = glob($uploaddir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
			unlink($file); // delete file
	}
	rmdir($uploaddir);
	global $db;
	$db->Execute("DELETE FROM ".tbl("config")." WHERE name='document_max_filesize' ");
	$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("documents").''
		);
	}
	
/**
 * Remove video_documents table from the database 
 */
function uninstallVideoDocuments() {
	global $db;
	$db->Execute( 'DROP TABLE  IF EXISTS '.tbl("video_documents").'');
}


uninstallVideoDocuments();
uninstallDocuments();
uninstallPluginAdminPermissions("documents");

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