<?php


/**
 * This file is used to install Embed Video Mod
 */

function install_cb_link_video_mode()
{
	global $db;
	$db->Execute("ALTER TABLE ".tbl('video')." ADD `remote_play_url` TEXT NOT NULL ");
	
}

install_cb_link_video_mode();

?>