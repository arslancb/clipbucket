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
$cdetails = $cbcollection->get_collection($c);
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);
$order = tbl("collection_items").".ci_id DESC";

if($cdetails)
{
	$type = $cdetails['type'];
	switch($type)
	{
		case "videos":
		{
			$items = $cbvideo->collection->get_collection_items_with_details($cdetails['collection_id'],$order);
		}
		break;
				
		case "pictures":
		{
			// Following to two lines will be un-commented once we have written picture.class
			//$items = $cbpicture->collection->get_collection_items_with_details($cdetails['collection_id'],NULL,$get_limit);
			//$total_rows = $cbpicture->collection->get_collection_items_with_details($cdetails['collection_id'],NULL,NULL,true);
		}
	}
	
	
	// Calling nesscary function for view collection
	call_view_collection_functions($cdetails);
	
	$total_pages = count_pages($total_rows,VLISTPP);

	//Pagination
	$pages->paginate($total_pages,$page);
		
	assign("c",$cdetails);
	assign("type",$type);
	assign("objects",$items);
	
	subtitle($cdetails['collection_name']);	
} else {
	e(lang("collect_not_exist"));
	$Cbucket->show_page = false;	
}

// Getting ready for Pagination
$page = mysql_clean($_GET['page']);

if($Cbucket->show_page)
{
	template_files('view_collection.html');
}

display_it();	

?>