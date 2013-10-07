<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

$id = mysql_clean($_GET['collection']);

if(isset($_POST['update_collection']))
{
	$cbcollection->update_collection();		
}

if(isset($_POST['delete_preview']))
{
	$id = mysql_clean($_POST['delete_preview']);
	$cbcollection->delete_thumbs($id);	
}

//Performing Actions
if($_GET['mode']!='')
{
	$cbcollection->collection_actions($_GET['mode'],$id);
}

$c = $cbcollection->get_collection($id);
switch($c['type'])
{
	case "videos":
	case "v":
	{
		$items = $cbvideo->collection->get_collection_items_with_details($c['collection_id'],NULL,4);
	}
	break;
	
	case "photos":
	case "p":
	{
		$items = $cbphoto->collection->get_collection_items_with_details($c['collection_id'],NULL,4);
	}
	break;
}
if(!empty($items))
	assign('objects',$items);
assign('data',$c);

subtitle("Edit Collection");
template_files('edit_collection.html');
display_it();
?>