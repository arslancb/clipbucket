<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();

		$url = mysql_clean($_GET['url']);
		include('group_inc.php');
		
		$details = $groups->GetDetails($url);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];
		
		if(!$groups->is_Joined($user,$group)){
		$msg = $LANG['grp_join_error1'];
		$show_leave = 'No';
		}
Assign('groups',$details);

//Joining Group

if(isset($_POST['join_group'])){
	$msg = $groups->LeaveGroup($user,$group);
	if(!$groups->is_Joined($user,$group)){
	Assign('show_succes','yes');
	$show_leave = 'No';
	}else{
	$show_leave = 'No';
	$msg = $LANG['grp_owner_err2'];
	}
}

Assign('subtitle',$LANG['leave'].' '.$details['group_name']);
Assign('msg',$msg);
Assign('show_leave',$show_leave);
Template('header.html');
Template('message.html');	
Template('leave_group.html');
Template('footer.html');
?>