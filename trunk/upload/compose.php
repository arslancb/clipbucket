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

$user	= $_SESSION['username'];


//Getting User Videos
	$sql = "SELECT * from video WHERE username = '".$user."'";
	$rs = $db->Execute($sql);
	$videos = $rs->getrows();
	Assign('videos',$videos);
	
//Getting User Contacts
	$sql = "SELECT * from contacts WHERE username = '".$user."'";
	$rs = $db->Execute($sql);
	$contacts = $rs->getrows();
	Assign('contacts',$contacts);
	@$msg = $_GET['msg'];

//Checking If Reply To A Message
if(isset($_POST['reply_to'])){
	$reply_to = $_POST['reply_to'];
	$username = $_POST['username'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];
	Assign('reply_to',$reply_to);
	Assign('default_username',$username);
	Assign('default_subj',$subject);
	Assign('default_message',$message);
	}

//Checking If Sendint Somene A Message
if(isset($_POST['send_message'])){
	$username = $_POST['username'];
	Assign('default_username',$username);
	}
//Sending Composed Message
	if(isset($_POST['send_msg'])){
		$message 	= mysql_clean($_POST['message']);
		$user	= mysql_clean($_POST['username']);
			if(empty($user)){
				$user = $_POST['user_list'];
				}
		$subj		= mysql_clean(clean($_POST['subj']));
		$video		= $_POST['video'];
		$reply_to	= $_POST['reply_to'];
		$msg =  $myquery->SendMessage($user,$_SESSION['username'],$subj,$message,$video,$reply_to,1);
		$values= array(
		'default_username' 	=> mysql_clean($_POST['username']),
		'default_user_list'	=> mysql_clean($_POST['user_list']),
		'default_subj' 		=> mysql_clean($_POST['subj']),
		'default_video' 	=> mysql_clean($_POST['video']),
		'default_message' 	=> mysql_clean($_POST['message']),
		'reply_to'			=> $_POST['reply_to']
		);
		while(list($name,$value) = each($values)){
		Assign($name,$value);
		}
	
	}
		
Assign('subtitle',$user.$LANG['title_crt_new_msg']);
Assign('msg',clean($msg));
Template('header.html');
Template('message.html');
Template('compose.html');
Template('footer.html');
?>