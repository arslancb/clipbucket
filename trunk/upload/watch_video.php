<?php

	/**
	* File: watch_video
	* Description: FIle used to display watch video page
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'watch_video');
	define("PARENT_PAGE",'videos');
	require 'includes/config.inc.php';
	global $hlp;
	$assign_arry = array();
	$userquery->perm_check('view_video',true);
	$pages->page_redir();
	$hlp->watch_video();
	display_it();
?>