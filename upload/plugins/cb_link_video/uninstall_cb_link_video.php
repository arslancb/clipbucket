<?php

/**
 * This file is used to install Embed Video Mod
 */

function uninstall_embed_video_mode()
{
	global $db;
	$db->Execute("ALTER TABLE ".tbl('video')." DROP `remote_play_url` ");
}
uninstall_embed_video_mode();
?>