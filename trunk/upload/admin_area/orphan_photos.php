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

if(isset($_GET['delete_photo']))
{
	$id = mysql_clean($_GET['delete_photo']);
	$cbphoto->delete_photo($id);		
}

if(isset($_POST['deleted_selected']))
{
	$total = $_POST['check_photo'];
	for($i=0;$i<$total;$i++)
	{
		$cbphoto->delete_photo($_POST['check_photo'][$i]);
	}
	$eh->flush();
	e($total." photos has been deleted successfully.","m");
}

//Multi-featured
if(isset($_POST['make_featured_selected']))
{
	$total = count($_POST['check_photo']);
	for($i=0;$i<$total;$i++)
	{
		$cbphoto->photo_actions('feature_photo',$_POST['check_photo'][$i]);	
	}
	$eh->flush();
	e($total." photos has been marked as <strong>Featured</strong>","m");
}

//Multi-unfeatured
if(isset($_POST['make_unfeatured_selected']))
{
	$total = count($_POST['check_photo']);
	for($i=0;$i<$total;$i++)
	{
		$cbphoto->photo_actions('unfeature_photo',$_POST['check_photo'][$i]);	
	}
	$eh->flush();
	e($total." photos has been marked as <strong>Unfeatured</strong>","m");
}

if(isset($_POST['move_selected']))
{
	$total = count($_POST['check_photo']);
	$new = mysql_clean($_POST['collection_id']);
	for($i=0;$i<$total;$i++)
	{
		$cbphoto->collection->change_collection($new,$_POST['check_photo'][$i]);
		$db->update(tbl('photos'),array('collection_id'),array($new)," photo_id = ".$_POST['check_photo'][$i]."");
	}
	$eh->flush();
	e($total." photo(s) have been moved to '<strong>".get_collection_field($new,'collection_name')."</strong>'","m");
		
}

$photos = $cbphoto->get_photos(array("get_orphans"=>TRUE));
$collection = $cbphoto->collection->get_collections(array("type"=>"photos"));
assign('photos',$photos);
assign('c',$collection);

subtitle("Orphan Photos");
template_files('orphan_photos.html');
display_it();
?>