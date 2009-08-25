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

if(@$_GET['updated']){
$msg = $LANG['usr_pof_upd_msg'];
}
//Updating User Record
	if(isset($_POST['update'])){
	$userquery->UpdateUserProfile($_COOKIE['userid']);
		}

//Update User Email Settings
	if(isset($_POST['update_email'])){
		$msg = $userquery->UpdateUserEmailSettings($_COOKIE['userid']);
	}

//Update User Email Settings
	if(isset($_POST['change_password'])){
		$msg = $userquery->ChangeUserPassword($_COOKIE['userid']);
	}
	
//Get User Details
	$user = $_COOKIE['userid'];
	$data = $userquery->GetUserData($user);	
	list($data['y'],$data['m'],$data['d']) = explode('-',$data['dob']);
	
	Assign('data',$data);

//Getting User Videos
	$sql = "SELECT * from video WHERE username = '".$data['username']."'";
	$rs = $db->Execute($sql);
	$videos = $rs->getrows();
	Assign('videos',$videos);

//GET setting options
@$show = $_GET['settings'];
Assign('show',$show);

@Assign('msg',$msg);
subtitle('my_account');
if(empty($_GET['show'])){
Template('header.html');
Template('message.html');
}
Template('user_account.html');
if(empty($_GET['show'])){
Template('footer.html');
}
?>