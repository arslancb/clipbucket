<?php

/* 
 ********************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ********************************************************************
*/
define("THIS_PAGE",'collections');
define("PARENT_PAGE",'collections');
require 'includes/config.inc.php';
$pages->page_redir();

$sort = $_GET['sort'];
$cond = array("category"=>mysql_clean($_GET['cat']),"date_span"=>$_GET['time']);
$content = mysql_clean($_GET['content']);

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
	
	case "most_items":
	{
		$cond['order'] = " total_objects DESC";
	}
	break;	
}

switch($content)
{
	case "videos":
	{
		$cond['type'] = "videos";	
	}
	break;
	
	case "photos":
	{
		$cond['type'] = "photos";	
	}
}

$cond['has_items'] = true;

//Getting Collection List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,COLLPP);
$clist = $cond;
$clist['limit'] = $get_limit;
$collections = $cbcollection->get_collections($clist);

Assign('collections', $collections);	

//Collecting Data for Pagination
$ccount = $cond;
$ccount['count_only'] = true;
$total_rows  = $cbcollection->get_collections($ccount);
$total_pages = count_pages($total_rows,COLLPP);

//Pagination
$pages->paginate($total_pages,$page);

subtitle(lang('collections'));
//Displaying The Template
template_files('collections.html');
display_it();
?>