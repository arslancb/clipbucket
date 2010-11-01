<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : FawazTahir, ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'manage_collections');
require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);


$mode = $_GET['mode'];
$cid = mysql_clean($_GET['cid']);

assign("mode",$mode);
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);

switch($mode)
{
	case "manage":
	default:
	{
		if(isset($_GET['delete_collection']))
		{
			$cid = clean($_GET['delete_collection']);
			$cbcollection->delete_collection($cid);	
		}
		
		$usr_collections = $cbcollection->get_collections(array('user'=>userid()));
		assign('usr_collects',$usr_collections);
	}
	break;
	
	case "add_new":
	{
		$reqFields = $cbcollection->load_required_fields();
		$otherFields = $cbcollection->load_other_fields();
		
		assign("fields",$reqFields);
		assign("other_fields",$otherFields);
		
		if(isset($_POST['add_collection']))
		{
			$cbcollection->create_collection($_POST);
		}
	}
	break;
	
	case "edit":
	case "edit_collection":
	case "edit_collect":
	{
			
		if(isset($_POST['update_collection']))
		{
			$cbcollection->update_collection($_POST);	
		}
		
		$collection = $cbcollection->get_collection($cid);
		$reqFields = $cbcollection->load_required_fields($collection);
		$otherFields = $cbcollection->load_other_fields($collection);
		
		assign("fields",$reqFields);
		assign("other_fields",$otherFields);
		assign('c',$collection);		
	}
	break;
	
	case "collection_items":
	case "items":
	case "manage_items":
	{
		$type = clean($_GET['type']);
		assign('type',$type);
		switch($type)
		{
			case "videos":
			{
				$objs = array();
				$items = $cbvideo->collection->get_collection_items($cid);
				if($items)
				{
					foreach($items as $item)
					{
						$objs[] = $cbvideo->get_video_details($item['object_id']);	
					}
				}
			}
			break;
		}
		$collection = $cbcollection->get_collection($cid);
		
		assign('c',$collection);
		assign('items',$items);
		assign('objs',$objs);
	}
	break;
		
}

subtitle(lang("manage_collections"));
template_files('manage_collections.html');
display_it();
?>