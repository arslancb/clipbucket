<?php
	require_once('../includes/common.php');

	/**
	 *	Delete database table ldap_client_config
	 */
	function uninstallExpandVideoManager()	{
		global $db;
		$db->Execute(
		'DROP TABLE IF EXISTS '.tbl("expand_video_manager").';'
		);
	}


	uninstallExpandVideoManager();
?>
