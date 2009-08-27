<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


//Adding Email Settings And Templates
if(isset($_POST['button'])){
	$rows = array(  'website_email'					=> mysql_clean($_POST['website_email']),
					'support_email'					=> mysql_clean($_POST['support_email']),
					'welcome_email'					=> mysql_clean($_POST['welcome_email']),
					'email_verification_template'	=> stripslashes($_POST['email_verification_template']),
					'welcome_message_template'		=> stripslashes($_POST['welcome_message_template']),
					'activate_request_template'		=> stripslashes($_POST['activate_request_template']),
					'share_video_template'			=> stripslashes($_POST['share_video_template'])
	
					);
	while(list($name,$value) = each($rows)){
	$myquery->Set_Email_Settings($name,$value);
	}
//Adding Email Settings Headers
	$headers = array(
					'email_verification_template'	=> mysql_clean($_POST['email_verification_header']),
					'welcome_message_template'		=> mysql_clean($_POST['welcome_message_header']),
					'activate_request_template'		=> mysql_clean($_POST['activate_request_header']),
					'share_video_template'			=> mysql_clean($_POST['share_video_header'])
					);
	while(list($name,$value) = each($headers)){
	$myquery->Set_Email_Settings_Headers($name,$value);
	}
	
//Write Templates
WriteEmailVerify();
WriteWelcomeMessage();
WriteActvationRequest();
WriteShareVideo();

}

$row = $myquery->Get_Email_Settings();
$header = $myquery->Get_Email_Settings_Headers();
Assign('row',$row);
Assign('header',$header);

Template('header.html');
Template('leftmenu.html');
Template('email_settings.html');
Template('footer.html');
?>