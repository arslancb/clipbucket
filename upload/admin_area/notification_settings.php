<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/


require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Videos');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Notification settings');
}
$mode = $_GET['mode'];


		if($_POST['update_notification'])
		{				
			$rows = array(
						  'notification_option');
			
							 			  
			foreach($rows as $field)
			{
				$value = $_POST[$field];
				$myquery->Set_Website_Details($field,$value);
			}
			
				
			e("Notification Settings Have Been Updated",'m');
			
			subtitle("Notification Settings");
		}


$row = $myquery->Get_Website_Details();
assign('row',$row);
subtitle("Notification Settings");
template_files('notification_settings.html');
display_it();

?>