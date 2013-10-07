<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","activation");
define("PARENT_PAGE",'signup');


require 'includes/config.inc.php';

if($userquery->udetails['usr_status']=='Ok'){
	redirect_to(BASEURL);
}

	/**
	 * Activating user account
	 */
	if(isset($_REQUEST['av_username']) || isset($_POST['activate_user']))
	{
		$user = mysql_clean($_REQUEST['av_username']);
		$avcode = mysql_clean($_REQUEST['avcode']);
		$userquery->activate_user_with_avcode($user,$avcode);
	}
			

	/**
	 * Requesting Activation Code
	 */

	if(isset($_POST['request_avcode']))
	{
		$email = mysql_clean($_POST['av_email']);
		$userquery->send_activation_code($email);
	}
		

template_files('activation.html');
display_it();
?>