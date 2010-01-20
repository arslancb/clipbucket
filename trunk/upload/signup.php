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
	
			assign('mode','signup_success');	
if($userquery->login_check('',true)){
	redirect_to(BASEURL);
}

	/**
	 * Signing up new user
	 */
	if(isset($_POST['signup'])){
		if(!$userquery->is_registeration_allowed())
			e(lang('usr_reg_err'));
		else
		{
			$signup = $userquery->signup_user($_POST);
			if($signup)
			{
				$udetails = $userquery->get_user_details($signup);
				$eh->flush();
				assign('udetails',$udetails);
				assign('mode','signup_success');
			}
		}
	}


//Login User

if(isset($_POST['login'])){
	$username = $_POST['username'];
	$username = mysql_clean(clean($username));
	$password = mysql_clean(clean($_POST['password']));
	if($userquery->login_user($username,$password))
	{
		if($_COOKIE['pageredir'])
			redirect_to($_COOKIE['pageredir']);
		else
			redirect_to(cblink(array('name'=>'my_account')));
	}
	
}

//Checking Ban Error
if(!isset($_POST['login']) && !isset($_POST['signup'])){
	if(@$_GET['ban'] == true){
		$msg = lang('usr_ban_err');
	}
}

subtitle(lang("signup"));
//Displaying The Template
template_files('signup.html');
display_it()
?>