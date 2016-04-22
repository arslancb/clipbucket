<?php

	/**
	* File: Photos
	* Description: Used to display list of photos
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'photos');
	define("PARENT_PAGE",'photos');
	require 'includes/config.inc.php';
	global $hlp;
	$pages->page_redir();
	$hlp->photos();
	display_it();
?>