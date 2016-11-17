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
}

/**
 * Add an entry into the CB config table in order to use multi search 
 */
function installConfig(){
	global $db;
	$db->insert(tbl("config"),array("name","value"),array("multisearchSection","yes"));	
}



/** install the plugin */
installConfig();
?>