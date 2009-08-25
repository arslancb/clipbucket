<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_COOKIE['username'];
$userid	= $_COOKIE['userid'];

//Removoing Favourite Video
if(isset($_POST['remove']) || isset($_POST['remove_x'])){
$favid = $_POST['fav_video'];
$msg = $myquery->RemoveFavourite($favid,$userid);
}

//Getting Videos List
	$limit = 30;
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";

	$sql 			= "SELECT * FROM video_favourites  WHERE userid='".$userid."' ORDER BY date_added DESC ";
	$data 			= $db->Execute($sql);
	$fav			= $data->getrows();
	$total_fav		= $data->recordcount()+0;
	
	for($id=0;$id<$total_fav;$id++){
	$vdata					= $myquery->GetVideoDetails($fav[$id]['videoid']);
	$fav[$id]['thumb1'] 	= GetName($vdata['flv']).'-1.jpg';
	$fav[$id]['title'] 		= $vdata['title'];
	$fav[$id]['videokey'] 	= $vdata['videokey'];
	$fav[$id]['url']		= VideoLink($vdata['videokey'],$vdata['title']);
	}
	
	Assign('fav',$fav);

subtitle('manage_favourites');
@Assign('msg',$msg);
if(@$_GET['show']!='favourites'){
Template('header.html');
Template('message.html');
Template('manage_favourites.html');
Template('footer.html');
}else{
Assign('full_view','no');
Template('manage_favourites.html');
}
?>