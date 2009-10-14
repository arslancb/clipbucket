<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author   : ArslanHassan																		|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define('THIS_PAGE','search');
require 'includes/config.inc.php';
$pages->page_redir();
						
$page = mysql_clean($_GET['page']);
$cbvid->search->key = $_GET['keywords'];
$cbvid->search->category = $_GET['category'];
$cbvid->search->date_margin = $_GET['datemargin'];
$cbvid->search->sort_by = $_GET['sort'];
$cbvid->search->limit = create_query_limit($page,VLISTPP);
$videos = $cbvid->search->search();


//Collecting Data for Pagination
$total_rows = $cbvid->search->total_results;
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);
Assign('videos', $videos);	


//Displaying The Template
template_files('search.html');
display_it();
?>