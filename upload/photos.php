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
$cond = array("category"=>mysql_clean($_GET['cat']),"date_span"=>$_GET['time'], "active"=>"yes");

//this variable is set to set table for order to get photos
$table_name = "photos";

switch($sort)
{	
	case "most_recent":
	default:
	{
		$cond['order'] = $table_name.".date_added DESC";
	}
	break;
	
	case "featured":
	{
		$cond['featured'] = "yes";
	}
	break;
	
	case "most_viewed":
	{
		$cond['order'] = $table_name.".views DESC";	
	}
	break;
	
	case "most_commented":
	{
		$cond['order'] = $table_name.".total_comments DESC";
	}
	break;
	
	case "top_rated":
	{
		$cond['order'] = $table_name.".rating DESC, ".$table_name.".rated_by DESC";
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



$collections = $cbcollection->get_collections($clist);

Assign('collections', $collections);


//Collecting Data for Pagination
$ccount = $cond;
$ccount['count_only'] = true;
$total_rows = get_photos($ccount);
$total_pages = count_pages($total_rows,MAINPLIST);

//Pagination
$link==NULL;
$extra_params=NULL;
$tag='<li><a #params#>#page#</a><li>';
$pages->paginate($total_pages,$page,$link,$extra_params,$tag);

subtitle(lang('photos'));
//Displaying The Template
template_files('photos.html');
display_it();
?>