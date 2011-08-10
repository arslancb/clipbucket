<?php

/* 
 ********************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ********************************************************************
*/
define("THIS_PAGE",'photos');
define("PARENT_PAGE",'photos');

require 'includes/config.inc.php';
$pages->page_redir();

$sort = $_GET['sort'];
$cond = array("category"=>mysql_clean($_GET['cat']),"date_span"=>$_GET['time']);

switch($sort)
{	
	case "most_recent":
	default:
	{
		$cond['order'] = " date_added DESC";
	}
	break;
	
	case "featured":
	{
		$cond['featured'] = "yes";
	}
	break;
	
	case "most_viewed":
	{
		$cond['order'] = " views DESC";	
	}
	break;
	
	case "most_commented":
	{
		$cond['order'] = " total_comments DESC";
	}
	break;
	
	case "top_rated":
	{
		$cond['order'] = " rating DESC, rated_by DESC";
	}
	break;	
}

//Getting Photo List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,MAINPLIST);
$clist = $cond;
$clist['limit'] = $get_limit;
$photos = get_photos($clist);
Assign('photos', $photos);	

//Collecting Data for Pagination
$ccount = $cond;
$ccount['count_only'] = true;
$total_rows = get_photos($ccount);
$total_pages = count_pages($total_rows,MAINPLIST);

//Pagination
$pages->paginate($total_pages,$page);

subtitle(lang('photos'));
//Displaying The Template
template_files('photos.html');
display_it();
?>