<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

$group = mysql_clean($_GET['group']);
//Updating Group
if(isset($_POST['update'])){
	$msg = $groups->UpdateGroup(2);
}
if(!$groups->GroupExists($group) || $group == 'Array'){
	$msg = $LANG['grp_exist_error'];
	$show_group = 'No';
}else{

		$details = $groups->GetDetailsid($group);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];

Assign('groups',$details);
}

//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	

//If Update is true
$update = mysql_clean($_GET['update']);
if($update==true){
	$msg = $LANG['grp_update_msg'];
}
		
//Assing Template
Assign('country',$signup->country());
Assign('msg',$msg);	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('edit_group.html');
Template('footer.html');
?>