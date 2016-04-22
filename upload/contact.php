<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
 Very Basic Contact Form
 Edit it according to your own need 
*/

define("THIS_PAGE","contact");

require 'includes/config.inc.php';

$name = post('name');
$email = post('email');
$reason = post('reason');
$message = post('message');

if(isset($_POST['contact']))
{
	
	if(empty($name))
		e(lang("name_was_empty"));
	elseif(empty($email) || !is_valid_syntax('email',$email))
		e(lang("invalid_email"));
	elseif(empty($reason))
		e(lang("pelase_enter_reason"));
	elseif(empty($message))
		e(lang("please_enter_something_for_message"));
	elseif(!verify_captcha())
		e(lang('usr_ccode_err'));
	else
	{
		$tpl = $cbemail->get_template('contact_form');
		$more_var = array
		('{name}'	=> substr($name,0,100),
		 '{email}'	=> substr($email,0,100),
		 '{reason}'		=> substr($reason,0,300),
		 '{message}'	=> $message,
		 '{ip_address}'	=> $_SERVER['REMOTE_ADDR'],
		 '{now}'	=> now(),
		);
		if(!is_array($var))
			$var = array();
		$var = array_merge($more_var,$var);
		$subj = $cbemail->replace($tpl['email_template_subject'],$var);
		$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
		
		//Now Finally Sending Email
		if(cbmail(array('to'=>SUPPORT_EMAIL,'from'=>$email,'subject'=>$subj,'content'=>$msg)))
		e(lang("email_send_confirm"),"m");
	}
}

subtitle(lang("contact_us"));

template_files('contact.html');
display_it();
?>