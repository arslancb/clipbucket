<?php
require_once('../includes/common.php');

/**
 * This file is used to install Embed Video Mod
 */

function uninstall_embed_video_mode()
{
	global $db;
	$db->Execute("ALTER TABLE `".tbl('video')."` DROP `embed_code` ");
}
uninstall_embed_video_mode();
?>