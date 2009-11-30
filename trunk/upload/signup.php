<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																			|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

define("THIS_PAGE","signup");
define("PARENT_PAGE","signup");

require 'includes/config.inc.php';

if($userquery->login_check('',true)){
	redirect_to(BASEURL);
}

//Checking If Registertatiosn Are Allowed or Not

if(!$signup->Registration()){
$msg = $LANG['usr_reg_err'];
}else{
	if(isset($_POST['signup'])){
		$signup->signup_user($_POST);
	}
}
subtitle('signup');

//Login User

if(isset($_POST['login'])){
	$username = $_POST['username'];
	$username = mysql_clean(clean($username));
	$password = mysql_clean(clean($_POST['password']));
	if($userquery->login_user($username,$password))
		redirect_to(BASEURL.login_success);
	
}

//Checking Ban Error
if(!isset($_POST['login']) && !isset($_POST['signup'])){
	if(@$_GET['ban'] == true){
		$msg = $LANG['usr_ban_err'];
	}
}

if($signup->Registration());
//Displaying The Template
template_files('signup.html');
display_it()
?>