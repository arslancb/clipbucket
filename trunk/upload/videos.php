<?php
/* 
 ********************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ********************************************************************
*/
define("THIS_PAGE",'videos');
define("PARENT_PAGE",'videos');
require 'includes/config.inc.php';
$pages->page_redir();
$userquery->perm_check('view_video',true);

//Setting Sort
$sort = $_GET['sort'];
$vid_cond = array('category'=>mysql_clean($_GET['cat']),'date_span'=>$_GET['time']);

switch($sort)
{
	case "most_recent":
	default:
	{
		$vid_cond['order'] = " date_added DESC ";
	}
	break;
	case "most_viewed":
	{
		$vid_cond['order'] = " views DESC ";
	}
	break;
	case "most_viewed":
	{
		$vid_cond['order'] = " views DESC ";
	}
	break;
	case "featured":
	{
		$vid_cond['featured'] = "yes";
	}
	break;
	case "top_rated":
	{
		$vid_cond['order'] = " rating DESC";
	}
	break;
	case "most_commented":
	{
		$vid_cond['order'] = " comments_count DESC";
	}
	break;
}

//Getting Video List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);
$vlist = $vid_cond;
$vlist['limit'] = $get_limit;
$videos = get_videos($vlist);
Assign('videos', $videos);	

//Collecting Data for Pagination
$vcount = $vid_cond;
$vcount['count_only'] = true;
$total_rows  = get_videos($vcount);
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);

subtitle(lang('videos'));
//Displaying The Template
template_files('videos.html');
display_it();
?>
 