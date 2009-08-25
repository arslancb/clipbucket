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

$page = $pages->show_admin_page(clean(@$_GET['settings']));
if(!empty($page)){
$pages->redirect($page);
}
if(@$_GET['msg']){
$msg = clean(@$_GET['msg']);
}	
$user = mysql_clean(@$_GET['userid']);


	//Check User Exists or Not
	if($userquery->Check_User_Exists($user)){
	//Update User
	if(isset($_POST['button'])){
	$msg = $signup->Admin_Edit_User();
	}
	//Get User Details
	$data = $userquery->GetUserData($user);	
	DoTemplate::assign('data',$data);
			
	}else{
	$msg[] = "User Doesn't Exist";
	}
	
//Assing Template
DoTemplate::assign('country',$signup->country());
DoTemplate::assign('msg',@$msg);	
DoTemplate::display(LAYOUT.'/header.html');
DoTemplate::display(LAYOUT.'/leftmenu.html');
DoTemplate::display(LAYOUT.'/message.html');
DoTemplate::display(LAYOUT.'/edit_member.html');
DoTemplate::display(LAYOUT.'/footer.html');

?>