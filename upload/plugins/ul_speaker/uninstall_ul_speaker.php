<?php
require_once('../includes/common.php');

//Function used to uninstall Plugin
	function uninstall_ul_speaker()
	{
		global $db;
		$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("speaker").''
		);
	}
	
	function uninstall_ul_speakerfunction()
	{
		global $db;
		$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("speakerfunction").''
		);
	}
	
	function uninstall_ul_video_speaker()
	{
		global $db;
		$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("video_speaker").''
		);
	}
	
	uninstall_ul_speaker();
	uninstall_ul_speakerfunction();
	uninstall_ul_video_speaker();
?>