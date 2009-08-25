<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$show_active = 1;
//Get Username and AVCode By GET Method
	if(isset($_GET['username'])){
		$username = mysql_clean($_GET['username']);
		$avcode	  = mysql_clean($_GET['avcode']);
		if($userquery->ActivateUser($username,$avcode)){
			$msg = $LANG['dear']."<strong>$username</strong>, ".$LANG['usr_activation_msg'];
			$show_active = 0;
			}else{
			$msg = $LANG['usr_activation_err'];
			$show_active = 1;
			}
		}
		
//Getting Username And AVCode By POST Method

	if(isset($_POST['activate'])){
		$username = mysql_clean($_POST['username']);
		$avcode	  = mysql_clean($_POST['avcode']);
		if($userquery->ActivateUser($username,$avcode)){
			$msg = $LANG['dear']."<strong>$username</strong>, ".$LANG['usr_activation_msg'];
			$show_active = 0;
			}else{
			$msg = $LANG['usr_activation_err'];
			$show_active = 1;
			}
		}
			
//Sendin Activation Code

	if(isset($_POST['request'])){
		$email = mysql_clean($_POST['email']);
			if($userquery->SendActivation($email)){
				$msg = $LANG['usr_activation_em_msg'];
				}else{
				$msg = $LANG['usr_activation_em_err'];
				}
		}
		
subtitle('activation');	
Assign('show_form',	$show_active);
Assign('msg',$msg);
Template('header.html');
Template('activation.html');
Template('footer.html');
?>