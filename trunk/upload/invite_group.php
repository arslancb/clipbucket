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
		include('group_inc.php');
		$details = $groups->GetDetails($url);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];
		if(empty($user)){
		$user 	= 	$_COOKIE['username'];
		}
		include('group_check.php');
		
//Getting User Contacts
	$sql = "SELECT * from contacts WHERE username = '".$_SESSION['username']."'";
	$rs = $db->Execute($sql);
	$videos = $rs->getrows();
	Assign('contacts',$videos);

//Sending Message
	if(isset($_POST['send'])){
	$subj = $user.' '.$LANG['grp_inv_msg1'].' '.$details['group_name'];
	$url = BASEURL.view_group_link.$details['group_url'];
	$msg = $groups->SendInvitation($user,$group,$subj,$url);
	}
		
//Chceking Logged in user is group user or not
	if(!$groups->is_joined($_SESSION['username'],$group)){
		Assign('join','yes');
	}else{
		Assign('join','no');
	}

Assign('groups',$details);



@Assign('subtitle',$details['group_name'].' '.$LANG['group']);
@Assign('msg',$msg);
@Assign('show_group',$show_group);
Template('header.html');
Template('message.html');	
Template('group_header.html');
Template('invite_group.html');
Template('footer.html');
?>