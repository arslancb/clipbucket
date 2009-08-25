<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->SuperAdminCheck();
$pages->page_redir();

//Updatingh Super Admin Details
if(isset($_POST['button'])){
	$msg = $userquery->UpdateSuperAdmin();
}

//Getting Details of the Super admin
$query = mysql_query("SELECT * FROM admin WHERE admin_id = '1' ");
$data = mysql_fetch_array($query);

Assign('data',$data);

Assign('msg',$msg);	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('super_admin.html');
Template('footer.html');

?>
