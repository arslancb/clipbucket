<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Move Video Up
if(isset($_GET['up'])){
	$id = mysql_clean($_GET['up']);
	$msg = $myquery->MovePickUp($id);
}

//Move Down Up
if(isset($_GET['down'])){
	$id = mysql_clean($_GET['down']);
	$msg = $myquery->MovePickDown($id);
}

//Removing From Editor's List
if(isset($_GET['remove'])){
	$id = mysql_clean($_GET['remove']);
	$msg = $myquery->DeleteEditorPick($id);
}
//Geting List Of Videos From Editor PIcks table
$query 			= "SELECT * FROM editors_picks ORDER BY sort ASC";
$data 			= $db->Execute($query);
$videos 		= $data->getrows();
$total_videos	= $data->recordcount()+0;

	for($id=0;$id<=$total_videos;$id++){
	$details 				= @$myquery->GetVideDetails($videos[$id]['videokey']);
	$videos[$id]['title']	= $details['title'];
	$videos[$id]['views']	= $details['views'];
	$videos[$id]['rating']	= pullRating($details['views'],true,false,true,'novote');
	}
Assign('total_videos',$total_videos);	
Assign('videos',$videos);
	
Assign('msg', @$msg);	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('editor_pick.html');
Template('footer.html');
?>