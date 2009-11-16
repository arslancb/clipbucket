<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$pages->page_redir();

	//Collect Data
	$gpData = $groups->get_groups();
	
	// Assign varibles
	assign('category',$groups->get_categories());
	assign('gps',$groups->list_groups());

template_files('groups.html');
display_it();
?>