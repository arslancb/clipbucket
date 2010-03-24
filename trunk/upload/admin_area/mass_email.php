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

//Sending Message To Multiple Users
if(isset($_POST['send_to_all']))
{
	$from 		= mysql_clean($_POST['from']);
	$subject	= mysql_clean($_POST['subj']);
	$message	= $_POST['msg'];
	
	$uarray = array();
	
	if(empty($from))
		e("From field was empty");
	if(empty($subject))
		e("Subject field was empty");
	if(empty($message))
		e("Message field was empty");
	
	//Checking if admin wants to send email to active users only....
	if($_POST['active']!='')
		$uarray['status'] = $_POST['active'];
	//Checking if admin wants to send email to banned or unbanned users only...
	if($_POST['ban']!='')
		$uarray['ban'] = $_POST['ban'];
	//Checking if admin wants to send email to specific leveled users
	if($_POST['level']!='')
		$uarray['level'] = $_POST['level'];
	//Checkinf if admin wants to send email to specfic categorized users
	if($_POST['category']!='')
		$uarray['category'] = $_POST['category'];
	
	
	if(!error())
	{
		$users = get_users($uarray);
		foreach($users as $user)
		{
			$keys = array("[username]","[email]","[datejoined]");
			$rplc = array($user['username'],$user['email'],$user['doj']);
			$msg = nl2br(str_replace($keys, $rplc, $message));
			//send_email($from,$data['email'],$subject,$msg);
			cbmail(array('from'=>$from,'to'=>$user['email'],'subject'=>$subj,'content'=>$message));
		}
		e('Your Email has Been Sent To All Users','m');
	}
}

	
	
//Send Message To Individual
if(isset($_POST['send_mail'])){
		$from 		= mysql_clean($_POST['from']);
		$subject	= mysql_clean($_POST['subj']);
		$message	= $_POST['msg'];
		$users = $_POST['to'];
		$new_users = explode(',',$users);
		
		if(empty($from))
		e("From field was empty");
		if(empty($subject))
			e("Subject field was empty");
		if(empty($message))
			e("Message field was empty");
		if(empty($users))
			e("Users field was empty");
		
		if(!error())
		{
			foreach($new_users as $theuser)
			{
				$user = $userquery->get_user_details($theuser);
				if($user)
				{
					$keys = array("[username]","[email]","[datejoined]");
					$rplc = array($user['username'],$user['email'],$user['doj']);
					$msg = nl2br(str_replace($keys, $rplc, $message));
					//send_email($from,$data['email'],$subject,$msg);
					cbmail(array('from'=>$from,'to'=>$user['email'],'subject'=>$subject,'content'=>$message));
				}
			}
			e('Your Email has Been Sent To Sepecified users','m');		
		}
}



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