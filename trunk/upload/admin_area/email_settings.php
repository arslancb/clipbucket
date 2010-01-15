<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->login_check('web_config_access');

$pages->page_redir();

//Updatingg email templates
if(isset($_POST['update']))
{
	for($i=0;$i<count($_POST['template_id']);$i++)
	{
		$params = array('id'=>$_POST['template_id'][$i],'subj'=>$_POST['subject'][$i],
						'msg'=>$_POST['message'][$i]);   
		$cbemail->update_template($params);
		$eh->flush();
		e("Email templates have been updated","m");
	}
}


subtitle("Email Settings");
template_files('email_settings.html');
display_it();
?>