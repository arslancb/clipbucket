<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$gpid = mysql_clean($_GET['group_id']);

	// Collecting Users List
	$userdata = $db->select("users","*");

	if(isset($_POST['update_group'])) {
		$cbgroup->edit_group($_POST);	
	}
	
	//Assign Varialbes
	assign('users',$userdata);
	assign('category',$cbgroup->get_categories());
	assign('group_details',$cbgroup->group_details($gpid));

template_files('edit_group.html');
display_it();

//require'../includes/admin_config.php';
//$userquery->login_check('admin_access');
//$pages->page_redir();
//
//$gpid = mysql_clean($_GET['group_id']);
////Updating Group
//if(isset($_POST['update'])){
//	$msg = $cbgroup->UpdateGroup(2);
//}
//if(!$cbgroup->GroupExists($group) || $group == 'Array'){
//	$msg = $LANG['grp_exist_error'];
//	$show_group = 'No';
//}else{
//
//		$details = $cbgroup->GetDetailsid($group);
//		$group 	= 	$details['group_id'];
//		$user 	= 	$_SESSION['username'];
//
//Assign('groups',$details);
//}
//
////Assigning Category List
//	$sql = "SELECT * from category";
//	$rs = $db->Execute($sql);
//	$total_categories = $rs->recordcount() + 0;
//	$category = $rs->getrows();
//	Assign('category', $category);	
//
////If Update is true
//$update = mysql_clean($_GET['update']);
//if($update==true){
//	$msg = $LANG['grp_update_msg'];
//}
//		
////Assing Template
//Assign('country',$signup->country());
//Assign('msg',$msg);	
//Template('header.html');
//Template('leftmenu.html');
//Template('message.html');
//Template('edit_group.html');
//Template('footer.html');
?>