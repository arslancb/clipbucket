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
	installPluginAdminPermissions("video_extensions", "Video extensions administration", "Allow Video extensions management");
}

/**
 * Create Table for external job encoder if not exists
 */
function installJobEncoderTable() {
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("job_encoder").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`name` varchar(100) NOT NULL ,
	  		`location` varchar(100) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
		);
}

/**
 * Create Table for external job types if not exists
 */
function installJobTypeTable() {
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("job_type").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`name` varchar(100) NOT NULL ,
	  		`command` varchar(100) NOT NULL ,
	  		`parameters` varchar(1024) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
		);
}

/**
 * Create Table for external jobs if not exists
 */
function installJobTable() {
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("job").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`name` varchar(100) NOT NULL ,
	  		`extension` varchar(20) NOT NULL ,
			`priority` int(11) NOT NULL ,
	  		`jobset` varchar(255) NOT NULL ,
			`idvideo` int(11) NULL ,
			`idjobtype` int(11) NOT NULL ,
	  		`idjobencoder` int(11) NOT NULL ,
			`status` enum("Standby","Encoded","Processing","Failed","Completed") NOT NULL DEFAULT "Standby",			
	  		`progress` int(11) NOT NULL DEFAULT 0,
			`parameters` varchar(1024) NULL ,
			`originalsrc` varchar(1024) NOT NULL ,
			`encodedsrc` varchar(1024) NOT NULL ,
  			`dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			`datestarted` timestamp NULL ,
  			`dateended` timestamp NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
			);
}


/**
 * Add a field to the video table
 */
function addOriginalVideoname(){
	global $db;
	$db->Execute("ALTER TABLE ".tbl("video")." ADD `original_videoname` varchar(150) NULL");
	
}

/** install the plugin */
installJobEncoderTable();
installJobTable();
installJobTypeTable();
addOriginalVideoname();
?>