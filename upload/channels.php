<?php

	/**
	* File: Channels
	* Description: Used to display list of channels (users)
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'channels');
	define("PARENT_PAGE",'channels');
	require 'includes/config.inc.php';
	global $hlp;
	$pages->page_redir();
	$userquery->perm_check('view_channels',true);
	$hlp->channels();
	display_it();

?>