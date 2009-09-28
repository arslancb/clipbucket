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
	CBTemplate::assign('data',$data);
			
	}else{
	$msg[] = "User Doesn't Exist";
	}
	
//Assing Template
CBTemplate::assign('country',$signup->country());
CBTemplate::assign('msg',@$msg);	
CBTemplate::display(LAYOUT.'/header.html');
CBTemplate::display(LAYOUT.'/leftmenu.html');
CBTemplate::display(LAYOUT.'/message.html');
CBTemplate::display(LAYOUT.'/edit_member.html');
CBTemplate::display(LAYOUT.'/footer.html');

?>