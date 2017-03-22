<?php


require_once('../includes/common.php');

/**
  *	Create the database configuration table
  */
function installExpandVideoManager() {
	global $db;
	$db->Execute(
		'CREATE TABLE '.tbl("expand_video_manager").' (
  		  `evm_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  `evm_plugin_url` varchar(255) NOT NULL,
		  `evm_zone` varchar(255) NOT NULL,
		  `evm_is_new_tab` tinyint(1) NOT NULL,
		  `evm_tab_title` varchar(100) NOT NULL
		) 
		ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}

/**
 * Install locales for this plugin
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	//importLangagePack($folder,'en');
	//importLangagePack($folder,'fr');
	installPluginAdminPermissions("expandVideoManager", "Expand Video Manager administration", "Allow Expand Video Manager management");
}


installExpandVideoManager();


?>
