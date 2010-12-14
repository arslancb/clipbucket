<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'view_item');
define("PARENT_PAGE",'view_collection');
require 'includes/config.inc.php';

$item = $_GET['item'];	
$type = $_GET['type'];
$cid  = $_GET['collection'];
$order = tbl("collection_items").".ci_id DESC";

if(empty($item))
	header('location:'.BASEURL);
else
{
	if(empty($type))
		header('location:'.$_COOKIE['pageredir']);
	else
	{
		assign('type',$type);
		
		switch($type)
		{
			case "videos":
			case "v":
			{
				global $cbvideo;
				$video = $cbvideo->get_video($item);
				if($video)
				{
					$info = $cbvideo->collection->get_collection_item_fields($cid,$video['videoid'],'ci_id,collection_id');
					$video = array_merge($video,$info[0]);
					
					increment_views($video['videoid'],'video');
					
					assign('object',$video);
					assign('c',$collect);
				} else {
					e("Item does not exist");
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
					$photo = array_merge($photo,$info[0]);
					
					increment_views($photo['photo_id'],'photo');
					
					assign('object',$photo);
					assign('c',$collect);
				} else {
					e("Item does not exist");
					$Cbucket->show_page = false;	
				}
			}
			break;
		}
	template_files('view_item.html');
	display_it();	
	}
		
}

?>