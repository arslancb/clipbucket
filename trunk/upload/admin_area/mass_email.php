<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com						
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('member_moderation');
$pages->page_redir();

if(!empty($_GET['email'])){
	Assign('email',$_GET['email']);
}

//Creating an mass email
if(isset($_POST['create_email']))
{
	if($cbemail->add_mass_email())
	{
		unset($_POST);
	}
}

//Deleting Email
if(isset($_GET['delete']))
{
	$del = mysql_clean($_GET['delete']);
	$cbemail->action($del,'delete');
}

//Sending Email
if(isset($_GET['send_email']))
{
	$eId = mysql_clean($_GET['send_email']);
	$email = $cbemail->get_email($eId);
	if($email)
	{	
		$msgs = $cbemail->send_emails($email);
		assign('msgs',$msgs);
		
		$email = $cbemail->get_email($eId);
		assign('send_email',$email);
	}
}

//Getting List of emails
$emails = $cbemail->get_mass_emails();
assign('emails',$emails);

//Category Array...
if(is_array($_POST['category']))
	$cats_array = array($_POST['category']);		
else
{
	preg_match_all('/#([0-9]+)#/',$_POST['category'],$m);
	$cats_array = array($m[1]);
}
$cat_array =	array(lang('vdo_cat'),
				'type'=> 'checkbox',
				'name'=> 'category[]',
				'id'=> 'category',
				'value'=> array('category',$cats_array),
				'hint_1'=>  lang('vdo_cat_msg'),
				'display_function' => 'convert_to_categories',
				'category_type'=>'user');
assign('cat_array',$cat_array);

//Displaying template...
subtitle("Mass Email");
template_files("mass_email.html");
display_it();
?>