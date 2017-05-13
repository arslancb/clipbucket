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
	installPluginAdminPermissions("chapters", "Chapters administration", "Allow Chapters management");
}

/**
 * Create Table for external job encoder if not exists
 */
function installChaptersTable() {
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("chapters").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`videoid` int(11) NOT NULL ,
			`time` float(10,2) NOT NULL,
			`title` varchar(100) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
		);
}

function installChaptersEditTab() {
	global $db;
	$sql = 'INSERT INTO '.tbl("expand_video_manager")." (`evm_id`, `evm_plugin_url`, `evm_zone`, `evm_is_new_tab`, `evm_tab_title`)".
			" VALUES ('', '".BASEDIR."/plugins/chapters/admin/set_chapters.php', ".
			"'expand_video_manager_left_panel', 1, '".lang("Chapters")."');";
	$db->Execute($sql);
}

/** install the plugin */
installChaptersTable();
installChaptersEditTab();
?>