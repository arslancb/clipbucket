<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.									|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define("THIS_PAGE",'videos');
require 'includes/config.inc.php';
$pages->page_redir();


//Getting Video List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);
$videos = $db->select("video",'*',$cond,$get_limit,"date_added DESC");
Assign('videos', $videos);	

//Collecting Data for Pagination
$total_rows = $db->count('video','*',$cond);
$records = $total_rows/VLISTPP;
$total_pages = round($records+0.49,0);

//Pagination
$pages->paginate($total_pages,$page);


//Displaying The Template
template_files('videos.html');
display_it();
?>
 