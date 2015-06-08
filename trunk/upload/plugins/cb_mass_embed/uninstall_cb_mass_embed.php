<?php
require_once('../includes/common.php');

/**
 * This file is used to uninstall Mass Embed Video Mod
 */

function uninstall_cb_mass_embed()
{
	global $db;
	$db->Execute("DROP TABLE ".tbl("mass_embed")." ");
	$db->Execute("DROP TABLE ".tbl("mass_embed_configs")." ");
}
uninstall_cb_mass_embed();
?>