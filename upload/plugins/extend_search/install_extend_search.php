<?php
require_once('../includes/common.php');


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