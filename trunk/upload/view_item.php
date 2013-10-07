<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'view_item');
define("PARENT_PAGE",'collections');

require 'includes/config.inc.php';

$item = $_GET['item'];	
$type = $_GET['type'];
$cid  = $_GET['collection'];
$order = tbl("collection_items").".ci_id DESC";

if($cbcollection->is_viewable($cid))
{
	if(empty($item))
		header('location:'.BASEURL);
	else
	{
		if(empty($type))
			header('location:'.BASEURL);
		else
		{
			assign('type',$type);
			$param = array("type"=>$type,"cid"=>$cid);
			$cdetails = $cbcollection->get_collections($param);
			$collect = $cdetails[0];
			switch($type)
			{
				case "videos":
				case "v":
				{
					global $cbvideo;
					$video = $cbvideo->get_video($item);
					
					if(video_playable($video))
					{
						//Getting list of collection items
						$page = mysql_clean($_GET['page']);
						$get_limit = create_query_limit($page,20);
						$order = tbl("collection_items").".ci_id DESC";

						$items = $cbvideo->collection->get_collection_items_with_details($cid,$order,$get_limit);
						assign('items',$items);
						
						assign('open_collection','yes');
						$info = $cbvideo->collection->get_collection_item_fields($cid,$video['videoid'],'ci_id,collection_id');
						if($info)
						{
							$video = array_merge($video,$info[0]);						
							increment_views($video['videoid'],'video');
							
							assign('object',$video);
							assign('user',$userquery->get_user_details($video['userid']));
							assign('c',$collect);						
							
							subtitle($video['title']);
							
						} else {
							e(lang("item_not_exist"));
							$Cbucket->show_page = false;
						}
					} else {
						e(lang("item_not_exist"));
						$Cbucket->show_page = false;	
					}
					
					
				}
				break;
				
				case "photos":
				case "p":
				{
					global $cbphoto;
					$photo = $cbphoto->get_photo($item);
					if($photo)
					{
						$info = $cbphoto->collection->get_collection_item_fields($cid,$photo['photo_id'],'ci_id');
						if($info)
						{
							$photo = array_merge($photo,$info[0]);							
							increment_views($photo['photo_id'],'photo');
							
							assign('object',$photo);
							assign('user',$userquery->get_user_details($photo['userid']));
							assign('c',$collect);
							
							subtitle($photo['photo_title'].' &laquo; '.$collect['collection_name']);
						} else {
							e(lang("item_not_exist"));
							$Cbucket->show_page = false;	
						}
					} else {
						e(lang("item_not_exist"));
						$Cbucket->show_page = false;	
					}
				}
				break;
			}
	
		}		
	}
} else 
	$Cbucket->show_page = false;

template_files('view_item.html');
display_it();
?>