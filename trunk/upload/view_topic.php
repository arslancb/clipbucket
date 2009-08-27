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
$topic = mysql_clean($_GET['tid']);
		if(!$groups->is_topic($topic) || $topic == 'Array'){
			$msg = $LANG['grp_tpc_err4'];
			$show_group_topic = 'No';
		}else{
		$topic_details = $groups->GetTopic($topic);
		$group 	 = $topic_details['group_id'];
		$user = $_SESSION['username'];
		if(empty($user)){
		$user 	= 	$_COOKIE['username'];
		}
		$details = $groups->GetDetailsid($group);
		$MustJoin = 'No';
		include('group_check.php');

//Delete Posts
if(isset($_POST['delete_topic'])){
	$groups->DeletePost($_POST['delete_topic']);
}

//Adding Comments / Posts
if(isset($_POST['add_comment'])){
	$comment 	= mysql_clean($_POST['comment']);
	$reply_to	= $_POST['reply_to'];
	$msg = $groups->AddComment($comment,$topic,$reply_to);
}	

//Getting Comments
$sql = "Select * FROM group_posts WHERE topic_id = '".$topic."'";
$data = $db->Execute($sql);
$total_replies = $data->recordcount() + 0;
$comments = $data->getrows();
Assign('comments',$comments);


//setting Comment Form Display setting
if(!empty($_SESSION['username'])){
	Assign('show_form','yes');
	Assign('user_name',$_SESSION['username']);
}
if(empty($_SESSION['username'])){
Assign('show_form','no');
Assign('user_name',$_SESSION['username']);
}
		if(!$groups->is_Joined($user,$group) && $_SESSION['username'] != $details['username']){
		Assign('joined','no');
		Assign('show_form','no');
		}elseif($_SESSION['username'] == $details['username'] || $groups->is_Joined($user,$group)){
		Assign('joined','yes');
		$show_form = 'yes';
		Assign('show_form','yes');
		}
		
Assign('groups',$details);
Assign('topic',$topic_details);
}

Assign('subtitle',$LANG['grp_title_topic'].$topic_details['topic_title']);
Assign('msg',$msg);
Assign('show_group_topic',$show_group_topic);
Template('header.html');
Template('message.html');	
Template('group_header.html');
Template('view_topic.html');
Template('footer.html');
?>