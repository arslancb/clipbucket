<?php
require_once('../includes/common.php');

/**
 * This file is used to install Embed Video Mod
 */

function uninstall_embed_video_mode()
{
	global $db;
	$db->Execute("ALTER TABLE `video` DROP `embed_code` ");
}
uninstall_embed_video_mode();
?>