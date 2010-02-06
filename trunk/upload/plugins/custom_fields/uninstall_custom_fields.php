<?php
require_once('../includes/common.php');

/**
 * This file is used to install Embed Video Mod
 */

function uninstall_embed_video_mode()
{
	global $db;
	$db->Execute("DELETE FROM ".tbl('phrases')." WHERE varname='cust_field_err'");
	$db->Execute("DROP TABLE `".tbl('custom_fields')."`");
}
uninstall_embed_video_mode();
?>