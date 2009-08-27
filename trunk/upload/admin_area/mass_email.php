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

if(!empty($_GET['email'])){
	Assign('email',$_GET['email']);
}
//Sending Message To Multiple Users
if(isset($_POST['send_mail'])){
	$from 		= mysql_clean($_POST['from']);
	$subject	= mysql_clean($_POST['subj']);
	$message	= $_POST['msg'];
	
	
//Sending Message To User
	$query = mysql_query("SELECT * FROM users");
		while($data = mysql_fetch_array($query)){
			$keys = array("[username]","[firstname]","[lastname]","[email]","[datejoined]");
			$rplc = array($data['username'],$data['first_name'],$data['last_name'],$data['email'],$data['doj']);
			$msg = nl2br(str_replace($keys, $rplc, $message));
			send_email($from,$data['email'],$subject,$msg);
		}
	$msg = 'Your Email has Been Sent To All Users';	
}


//Send Message To Individual
if(isset($_POST['email'])){
		$from 		= mysql_clean($_POST['from']);
		$subject	= mysql_clean($_POST['subj']);
		$message	= $_POST['msg'];
		$users = $_POST['to'];
		$new_users = explode(',',$users);
		foreach($new_users as $user){
			send_email($from,$user,$subject,$message);
		}
	$msg = 'Email Has Been Sent';
}
	
Assign('msg',@$msg);

Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('mass_email.html');
Template('footer.html');

?>