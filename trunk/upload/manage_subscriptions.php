<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_COOKIE['username'];

//Unsubscribing A Video
if(isset($_POST['unsubscribe']) || isset($_POST['unsubscribe_x'])){
$subid = $_POST['un_sub'];
$msg = $myquery->UnSubscribe($subid,$user);
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

	$sql 			= "SELECT * FROM subscriptions  WHERE subscribed_user='".$user."' ORDER BY date_added DESC ";
	$data 			= $db->Execute($sql);
	$subs			= $data->getrows();
	$total_subs		= $data->recordcount()+0;
	
	for($id=0;$id<$total_subs;$id++){
	$subs[$id]['subscribers'] 	= $myquery->GetSubscribers($subs[$id]['subscribed_to']);
	$subs[$id]['videos'] 		= $myquery->GetTotalVideos($subs[$id]['subscribed_to']);
	}
	
	Assign('subs',$subs);


subtitle('manage_video');
@Assign('msg',$msg);
if($_GET['show']!='subscriptions'){
Template('header.html');
Template('message.html');
Template('manage_subscriptions.html');
Template('footer.html');
}else{
Assign('full_view','no');
Template('manage_subscriptions.html');
}
?>