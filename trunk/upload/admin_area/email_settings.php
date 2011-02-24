<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');

$pages->page_redir();

//Updatingg email templates
if(isset($_POST['update']))
{
	$templates = $cbemail->get_templates();
	
	foreach($templates as $template)
	{
		$params = array('id'=>$template['email_template_id'],'subj'=>$_POST['subject'.$template['email_template_id']],
						'msg'=>$_POST['message'.$template['email_template_id']]);   
		$cbemail->update_template($params);
		$eh->flush();
		e("Email templates have been updated","m");
	}
}

if(isset($_POST['update_settings'])){
	$configs = $Cbucket->configs;
	
	$rows = array(
				  	'mail_type',
				  	'smtp_host',
					'smtp_user',
				  	'smtp_pass',
					'smtp_auth',
					'smtp_port'
					);
	

	foreach($rows as $field)
	{
		$value = ($_POST[$field]);
		$myquery->Set_Website_Details($field,$value);
	}
	e("Email Settings Have Been Updated",'m');

}

$row = $myquery->Get_Website_Details();
Assign('row',$row);

subtitle("Email Settings");
template_files('email_settings.html');
display_it();
?>