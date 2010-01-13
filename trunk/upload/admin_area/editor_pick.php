<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Move Video Up
if(isset($_GET['move_up'])){
	$id = mysql_clean($_GET['move_up']);
	move_pick_up($id);
}

//Move Down Up
if(isset($_GET['move_down'])){
	$id = mysql_clean($_GET['move_down']);
	move_pick_down($id);
}

//Removing
if(isset($_GET['remove'])){
	$id = mysql_clean($_GET['remove']);
	remove_vid_editors_pick($id);
}

assign('videos',get_ep_videos());
assign('max',get_highest_sort_number());
assign('min',get_lowest_sort_number());
	
	
subtitle("Editor's Pick");
template_files('editor_pick.html');
display_it();
?>