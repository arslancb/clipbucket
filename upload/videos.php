<?php
	
	/**
	* File: videos
	* Description: Used to display list of videos
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'videos');
	define("PARENT_PAGE",'videos');
	require 'includes/config.inc.php';
	global $hlp;
	$pages->page_redir();
	$userquery->perm_check('view_videos',true);
	$hlp->videos();
	display_it();
?>