<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();

//Get User Details
	$user 		= $_SESSION['userid'];
	$username 	= $_SESSION['username'];
	$data = $userquery->GetUserData($user);	

//Get Number Of Objects Of User
	$data['uploaded_watched'] 		= $myquery->GetUploadedWatched($username);
	$data['subscribed_user']  		= $myquery->GetSubscribers($username);
	$data['user_subscriptions'] 	= $myquery->GetSubscriptions($username);
	$data['total_channel_comments']	= $myquery->GetTotalChannelComments($username);
	$data['total_favourites']		= $myquery->GetTotalFavourites($user);
	$data['create_groups']			= $myquery->GetUserCreateGroups($username);
	$data['joined_groups']			= $myquery->GetUserJoinGroups($username);
	Assign('data',$data);
	
subtitle('my_account');
@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('myaccount.html');
Template('footer.html');

?>