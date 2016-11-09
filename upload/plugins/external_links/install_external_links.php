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
	installPluginAdminPermissions("links", "External links administration", "Allow external links management");
}

/**
 * Creating Table for links if not exists 
 */
function install_links() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("links").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`title` varchar(1024) NOT NULL ,
	  		`url` varchar(1024) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}


/**
 * Creating a join Table for video and links if not exists 
 */
function install_video_links() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_links").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`link_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}

/** install the plugin */
install_links();
install_video_links();
?>