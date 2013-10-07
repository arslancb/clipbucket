<?php
/**
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://www.opensource.org/licenses/attribution.php
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/		
				
			function replace_quotes($string){
			$pattern[0] = '/"/';
			$rep[0] ="'";
			$rp1 = preg_replace($pattern, $rep, $string);
			return $rp1;
			}
			
			//This Function Is Used To Write Email Verification Template
			function WriteEmailVerify(){
			$myquery 	= new myquery();
			$email_data = $myquery->Get_Email_Settings();
			$email_verify = $email_data['email_verification_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/email_verify.template.php", "w");
					fwrite($fp, '
					<?php 
					$body ="'.replace_quotes($email_verify).'" 
					?>
					');
			$email_header = $myquery->Get_Email_Settings_headers();
			$email_verify_header = $email_header['email_verification_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/email_verify.header.php", "w");
					fwrite($fp, '
					<?php 
					$subj ="'.$email_verify_header.'" 
					?>
					');
			}
			
			//This Function Is Used To Write Welcome Message Template
			function WriteWelcomeMessage(){
			$myquery 	= new myquery();
			$email_data = $myquery->Get_Email_Settings();
			$email_verify = $email_data['welcome_message_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/welcome_message.template.php", "w");
					fwrite($fp, '
					<?php 
					$body ="'.replace_quotes($email_verify).'" 
					?>
					');
					
			$email_header = $myquery->Get_Email_Settings_headers();
			$email_verify_header = $email_header['welcome_message_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/welcome_message.header.php", "w");
					fwrite($fp, '
					<?php 
					$subj ="'.$email_verify_header.'" 
					?>
					');
			}
			
			//This Function Is Used To Write Welcome Message Template
			function WriteActvationRequest(){
			$myquery 	= new myquery();
			$email_data = $myquery->Get_Email_Settings();
			$email_verify = $email_data['activate_request_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/activation_request.template.php", "w");
					fwrite($fp, '
					<?php 
					$body ="'.replace_quotes($email_verify).'" 
					?>
					');
					
			$email_header = $myquery->Get_Email_Settings_headers();
			$email_verify_header = $email_header['activate_request_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/activation_request.header.php", "w");
					fwrite($fp, '
					<?php 
					$subj ="'.$email_verify_header.'" 
					?>
					');
			}
			
			//This Function Is Used To Write Share Video Template
			function WriteShareVideo(){
			$myquery 	= new myquery();
			$email_data = $myquery->Get_Email_Settings();
			$email_verify = $email_data['share_video_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/share_video.template.php", "w");
					fwrite($fp, '
					<?php 
					$body ="'.replace_quotes($email_verify).'" 
					?>
					');
					
			$email_header = $myquery->Get_Email_Settings_headers();
			$email_verify_header = $email_header['share_video_template'];
					$fp = fopen(BASEDIR."/includes/email_templates/share_video.header.php", "w");
					fwrite($fp, '
					<?php 
					$subj ="'.$email_verify_header.'" 
					?>
					');
			}

?>