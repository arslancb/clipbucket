<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();
$userquery->logincheck();

$url = mysql_clean($_GET['url']);
if(isset($_POST['update'])){
	$msg = $groups->UpdateGroup();
}
		include('group_inc.php');
		//Delete Group
		$details = $groups->GetDetails($url);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];
		if($details['username'] !== $user){
			$msg = $LANG['grp_owner_err1'];
			$show_delete = 'No';
		}
Assign('groups',$details);

if(isset($_POST['delete_group'])){
	$groups->DeleteGroup($_POST['group_id']);
	Assign('show_succes','yes');
	$show_delete = 'No';
}

Assign('show_delete',@$show_delete);
Assign('subtitle',' Edit '.$details['group_name']);
Assign('msg',@$msg);	
Template('header.html');
Template('message.html');	
Template('delete_group.html');
Template('footer.html');
?>