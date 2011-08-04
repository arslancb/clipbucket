<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ******************************************************************
*/
define("THIS_PAGE",'view_collection');
define("PARENT_PAGE",'collections');

require 'includes/config.inc.php';
$pages->page_redir();

$c = mysql_clean($_GET['cid']);
$type = mysql_clean($_GET['type']);

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,COLLIP);
$order = tbl("collection_items").".ci_id DESC";

if($cbcollection->is_viewable($c))
{
	$param = array("type"=>$type,"cid"=>$c);
	$cdetails = $cbcollection->get_collection($c,"AND ".tbl($cbcollection->section_tbl).".type = '$type' ");
	
	if($cdetails)
	{
	switch($type)
	{
		case "videos":
		case "video":
		case "v":
		{
			$items = $cbvideo->collection->get_collection_items_with_details($c,$order,$get_limit);
			//$count = $cbvideo->collection->get_collection_items_with_details($c,NULL,NULL,TRUE);
		}
		break;
		
		case "photos":
		case "photo":
		case "p":
		{
			$items = $cbphoto->collection->get_collection_items_with_details($c,$order,$get_limit);
			//$count = $cbphoto->collection->get_collection_items_with_details($c,NULL,NULL,TRUE);
		}
		break;
	}
	
	// Calling nesscary function for view collection
	call_view_collection_functions($cdetails[0]);
	$total_pages = count_pages($count,COLLIP);
	
	//Pagination
	$pages->paginate($total_pages,$page);
	
	assign('objects',$items);	
	assign("c",$cdetails);
	assign("type",$type);
	assign("cid",$c);	
	subtitle($cdetails['collection_name']);
	} else {
		e(lang("collection_not_exists"));
		$Cbucket->show_page = false;	
	}
} else {
	$Cbucket->show_page = false;	
}


template_files('view_collection.html');
display_it();	
?>