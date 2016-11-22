<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Install locales for this plugin and set admin permissions
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	importLangagePack($folder,'en');
	importLangagePack($folder,'fr');
	installPluginAdminPermissions("documents", "Documents administration", "Allow documents management");
}


/**
 * Creating Table for documents if not exists 
 */
function installDocuments(){
	/** Create a folder for documents storing */
	$uploaddir = BASEDIR."/files/documents";
	if (is_dir($uploaddir)){ 
		$files = glob($uploaddir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		rmdir($uploaddir);
	}
	$folder = mkdir($uploaddir,0775);
	/** Set the document max file size */
	global $db;
	$db->insert(tbl("config"),array("name","value"),array("document_max_filesize","25000000"));	
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("documents").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`documentkey` varchar(50) NOT NULL ,
	  		`title` varchar(1024) NOT NULL ,
	  		`filename` varchar(1024) NOT NULL ,
	  		`mimetype` varchar(256) NOT NULL ,
			`storedfilename` varchar(128) NOT NULL ,
			`size` int(11) NOT NULL ,
	  		`creationdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}


/**
 * Creating a join Table for video and documents if not exists 
 */
function installVideoDocuments() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_documents").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`document_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}


/** install the plugin */
installDocuments();
installVideoDocuments();
?>