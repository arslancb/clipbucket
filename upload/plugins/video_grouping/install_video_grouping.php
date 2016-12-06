<?php

require_once('../includes/common.php');

/**
 * Install locales for this plugin
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	importLangagePack($folder,'en');
	importLangagePack($folder,'fr');
	installPluginAdminPermissions("videogrouping", "Video grouping administration", "Allow video grouping management");
}

/**
 * Create Table vdogrouping_type if not exists which will contain the list of kind of grouping.
 */
function installVdogroupingType() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("vdogrouping_type")." (
  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  		`name` varchar(100) NOT NULL, 
		`in_thumb` BOOLEAN NOT NULL DEFAULT '0',
		`in_menu` BOOLEAN NOT NULL DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
	);
}

/**
 * Create Table vdogrouping if not exists 
 */
function installVdogrouping() {	
	global $db;
	$db->Execute(
		// WARNING ! Use `` instead of '' for fields - SMARTY restriction
		"CREATE TABLE IF NOT EXISTS ".tbl('vdogrouping')." (
		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`grouping_type_id` int(11) NOT NULL,
		`name` varchar(128) NOT NULL,
		`place` bigint(5) NOT NULL DEFAULT '1',
		`description` varchar(2048) NOT NULL DEFAULT 'Default',
		`in_menu` BOOLEAN NOT NULL DEFAULT '1',
		`color` varchar(50) NOT NULL DEFAULT '#999999',
		`thumb` BOOLEAN DEFAULT '1',
		`thumb_url` varchar(255) NOT NULL DEFAULT 'default.png'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
	);
	// Add a default value
	$db->Execute("INSERT INTO  ".tbl('vdogrouping')." (name, is_default) VALUES ('Default',1)");
	// Add database constraint
	$db->Execute(
			'ALTER TABLE '.tbl("video_grouping").'
  			ADD CONSTRAINT `grouping_type_id_fk_vdogrouping_type` FOREIGN KEY (`grouping_type_id`) REFERENCES '.tbl("vdogrouping_type").' (`id`);'
	);
}


/**
 * Create Table for joining video to video grouping
 */
function installVideoGrouping() {
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("video_grouping").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`vdogrouping_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
	$db->Execute(
			'ALTER TABLE '.tbl("video_grouping").'
  			ADD UNIQUE KEY `video_id` (`video_id`,`vdogrouping_id`);'
	);
	$db->Execute(
			'ALTER TABLE '.tbl("video_grouping").'
			ADD CONSTRAINT `vdogrouping_id_fk_vdogrouping_id` FOREIGN KEY (`vdogrouping_id`) REFERENCES '.tbl("vdogrouping").' (`id`);'
	);
}


/**
 * Add default thumb file for video grouping
 */
function installVideoGroupingFiles() {
	$uploaddir = BASEDIR."/files/thumbs/video_grouping";
	if (is_dir($uploaddir)){
		$files = glob($uploaddir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		rmdir($uploaddir);
	}
	$folder = mkdir($uploaddir,0777);
	if ($folder){
		if(!copy(PLUG_DIR."/video_grouping/default.png", $uploaddir."/default.png")){
			die(lang("unable_to_copy_default_image"));
		}
	} else {
		die(lang("unable_to_create_folder"));
	}
}


/** install the plugin */
installVideoGroupingFiles();
installVdoGroupingType();
installVdoGrouping();
installVideoGrouping();

?>