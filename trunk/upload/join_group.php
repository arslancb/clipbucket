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
		if($details['group_type'] == 2){
			if(!$groups->is_userinvite($_SESSION['username'],$details['username'],$details['group_id'])){
			$msg = $LANG['grp_prvt_err1'];
			$show_join = 'No';
			}
		}
		if($details['active'] == 'no'){
		$msg = $LANG['grp_inact_error'];
		$show_join = 'No';
		}
		if($groups->is_Joined($user,$group)){
		$msg = $LANG['grp_join_error'];
		$show_join = 'No';
		}
Assign('groups',$details);
//Joining Group

if(isset($_POST['join_group'])){
	if($details['group_type'] == '1'){
		$active = 'no';
	}else{
		$active = 'yes';
	}
	$msg = $groups->JoinGroup($user,$group,$active);
	if($groups->is_Joined($user,$group)){
	Assign('show_succes','yes');
	$show_join = 'No';
	}
}

@Assign('subtitle',$LANG['join'].' '.$details['group_name']);
@Assign('msg',$msg);
@Assign('show_join',$show_join);
Template('header.html');
Template('message.html');	
Template('join_group.html');
Template('footer.html');
?>