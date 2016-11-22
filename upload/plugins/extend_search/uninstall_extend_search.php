<?php
require_once('../includes/common.php');

/**
 * Remove documents table from the database 
 */
function uninstallConfig() {
	global $db;
	$db->Execute("DELETE FROM ".tbl("config")." WHERE name='multisearchSection' ");
}
	

uninstallConfig();

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