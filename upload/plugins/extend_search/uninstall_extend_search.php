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

?>