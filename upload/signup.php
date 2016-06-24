<?php

/* 
 *************************************************************
 | Copyright (c) 2007-2016 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan								
 | @ Software  : ClipBucket
 | @ Modified : June 9, 2016 by Saqib Razzaq			
 *************************************************************
*/

	define("THIS_PAGE","signup");
	define("PARENT_PAGE","signup");

	require 'includes/config.inc.php';
	global $Cbucket,$userquery;
	if($userquery->login_check('',true)) {
		redirect_to(BASEURL);
	}

	/**
	 * Function used to call all signup functions
	 */
	if(cb_get_functions('signup_page')){
		cb_call_functions('signup_page'); 
	} 
		
		
	/**
	 * Signing up new user
	 */
	if(!config('allow_registeration')){
		assign('allow_registeration',lang('usr_reg_err'));
        //
	}
				
	if(isset($_POST['signup'])){
		if(!config('allow_registeration')){
			e(lang('usr_reg_err'));
		} else {
			$form_data = $_POST;
			$signup_data = $form_data;
			$signup_data['password'] = mysql_clean(clean($signup_data['password']));
			$signup_data['cpassword'] = mysql_clean(clean($signup_data['cpassword']));
			$signup_data['email'] = mysql_clean($signup_data['email']);
			$signup = $userquery->signup_user($signup_data,true);

			// checking if user signup was successful
			if($signup) {

				// user signed up, lets get his details
				$udetails = $userquery->get_user_details($signup);
				$eh->flush();
				assign('udetails',$udetails);
				if (empty($Cbucket->configs['email_verification'])) {
					// login user and redirect to home page
					$userquery->login_as_user($udetails['userid']);
					header("Location: ".BASEURL);
				} else {
					assign('mode','signup_success');
				}
			}
		}
	}

	//Login User
	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$username = mysql_clean(clean($username));
		$password = mysql_clean(clean($_POST['password']));
		
		$remember = false;
		if($_POST['rememberme'])
			$remember = true;
			
		if($userquery->login_user($username,$password,$remember)) {
			if($_COOKIE['pageredir']) {
				redirect_to($_COOKIE['pageredir']);
			} else {
				redirect_to(cblink(array('name'=>'my_account')));
			}
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
