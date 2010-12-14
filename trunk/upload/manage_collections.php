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
$order = tbl("collection_items").".date_added DESC";

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
		
		if($_POST['delete_selected'])
		{
			$count = count($_POST['check_col']);
			for($i=0;$i<$count;$i++)
			{
				$cbcollection->delete_collection($_POST['check_col'][$i]);	
			}
			$eh->flush();
			e("selected_collects_del","m");
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
				if(isset($_POST['delete_selected']))
				{
					$count = count($_POST['check_item']);
					for($i=0;$i<$count;$i++)
					{
						$cbvideo->collection->remove_item($_POST['check_item'][$i],$cid);
					}
					$eh->flush();
					e(sprintf("selected_items_removed","videos"),"m");
				}
				$objs = $cbvideo->collection->get_collection_items_with_details($cid,$order);
			}
			break;
			
			case "photos":
			{
				if(isset($_POST['delete_selected']))
				{
					$count = count($_POST['check_item']);
					for($i=0;$i<$count;$i++)
					{
						$cbphoto->collection->remove_item($_POST['check_item'][$i],$cid);
					}
					$eh->flush();
					e(sprintf("selected_items_removed","pictures"),"m");
				}
				$objs = $cbphoto->collection->get_collection_items_with_details($cid,$order);
			}
			break;
		}
		$collection = $cbcollection->get_collection($cid);
		
		assign('c',$collection);
		assign('objs',$objs);
	}
	break;
		
}

subtitle(lang("manage_collections"));
template_files('manage_collections.html');
display_it();
?>