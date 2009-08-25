<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																			|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';

if($userquery->login_check('',true)){
	redirect_to(BASEURL);
}

//Checking If Registertatiosn Are Allowed or Not

if(!$signup->Registration()){
$msg = $LANG['usr_reg_err'];
}else{
	if($row['captcha_type']==2){
		Assign('captcha',BASEURL.'/includes/classes/captcha/img.php');
	}elseif($row['captcha_type']==1){
		Assign('captcha',BASEURL.'/includes/classes/captcha_simple.img.php');
	}
	
	if(isset($_POST['signup'])){
		$msg = $signup->SignUpUser();
			if($msg=='success'){
			redirect_to(BASEURL.signup_success);
			}
	}
}
subtitle('signup');

//Login User

if(isset($_POST['login'])){
	$username = $_POST['username'];
	$username = mysql_clean(clean($username));
	$password = mysql_clean(clean($_POST['password']));
	/*if($userquery->userlogin($username,$password)=='loggedin'){
	redirect_to(BASEURL.login_success);
	}else{
		$error = $userquery->userlogin($username,$password);
			if($error == 'banned'){
				$msg = $LANG['usr_ban_err'];
			}else{
			$msg= $LANG['usr_login_err'];
			}
	}*/
	if($userquery->login_user($username,$password))
		redirect_to(BASEURL.login_success);
	
}

//Checking Ban Error
if(!isset($_POST['login']) && !isset($_POST['signup'])){
	if(@$_GET['ban'] == true){
		$msg = $LANG['usr_ban_err'];
	}
}


@Assign('msg',$msg);
Template('header.html');
Template('message.html');
if($signup->Registration())
Template('signup.html');
Template('footer.html');
?>