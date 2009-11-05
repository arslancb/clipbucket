<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->login_check('video_moderation');

//Getting Video List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$videos = $cbvid->action->get_flagged_objects($get_limit);
Assign('videos', $videos);	

//Collecting Data for Pagination
$total_rows  = $cbvid->action->count_flagged_objects();
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);
	
template_files('video_manager.php');
display_it();
?>