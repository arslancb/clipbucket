<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';

$action = mysql_clean($_GET['action']);
//Reset Password
	if(isset($_POST['reset'])){
		$msg = $userquery->ResetPassword(1);
	}
	
	//Reseting
	if($action =='reset_pass'){
	$msg = $userquery->ResetPassword(2);	
	}
	
//Recover Username

	if(isset($_POST['recover'])){
		$msg = $userquery->RecoverUsername();
	}
		

if($row['captcha_type']==2){
Assign('captcha',BASEURL.'/includes/classes/captcha/img.php');
}elseif($row['captcha_type']==1){
Assign('captcha',BASEURL.'/includes/classes/captcha_simple.img.php');
}

Assign('subtitle',$LANG['title_forgot']);

Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('forgot.html');
Template('footer.html');
?>