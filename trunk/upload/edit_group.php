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
		//Updating Group
		if(isset($_POST['update'])){
			$msg = $groups->UpdateGroup();
		}
		//Checks Group exists Or Not
		include('group_inc.php');

		$details = $groups->GetDetails($url);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];
		if($details['username'] != $user){
			$msg = $LANG['grp_owner_err1'];
			$show_group = 'No';
		}
Assign('groups',$details);


//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	

//If Update is true
$update = mysql_clean(@$_GET['update']);
if($update==true){
	$msg = $LANG['grp_update_msg'];
}	
Assign('show_group',@$show_group);
Assign('subtitle',' Edit '.$details['group_name']);
Assign('msg',@$msg);
Template('header.html');
Template('message.html');	
Template('edit_group.html');
Template('footer.html');
?>