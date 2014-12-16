<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","edit_account");

require 'includes/config.inc.php';
$userquery->logincheck();

//Updating Profile
if(isset($_POST['update_profile']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user($array);
}

//Updating Avatar
if(isset($_POST['update_avatar_bg']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user_avatar_bg($array);
}

if(isset($_FILES["coverPhoto"])){
	$array = $_FILES;
	$array['userid'] = userid();
	$coverUpload = $userquery->updateCover($array);
	$timeStamp = time();
	$response = array(
		"status" => $coverUpload["status"],
		"msg" => $coverUpload["msg"],
		"url" => $userquery->getCover(userid()) . "?{$timeStamp}",
		);
	echo json_encode($response);
	die();
}

//Changing Email
if(isset($_POST['change_email']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_email($array);
}

//Changing User Password
if(isset($_POST['change_password']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_password($array);
}

//Banning Users
if(isset($_POST['block_users']))
{
	$userquery->block_users($_POST['users']);
}

$mode = $_GET['mode'];


assign('mode',$mode);

switch($mode)
{
	case 'account':
	{
		assign('on','account');
		assign('mode','account_settings');
	}
	break;
	case 'profile':
	{
		assign('on','profile');
		assign('mode','profile_settings');
	}
	break;
	
	case 'avatar_bg':
	{
		Assign('extensions', $Cbucket->get_extensions());
		assign("coverPhoto", $userquery->getCover(userid()));
		assign('mode','avatar_bg');
	}
	break;

	case 'channel_bg':
	{
		Assign('extensions', $Cbucket->get_extensions());
		assign("coverPhoto", $userquery->getCover(userid()));
		assign('mode','channel_bg');
	}
	break;

	case 'change_cover':
	{

		Assign('extensions', $Cbucket->get_extensions());
		assign("coverPhoto", $userquery->getCover(userid()));
		assign('mode','change_cover');
	}
	break;
	
	case 'change_email':
	{
		assign('mode','change_email');
	}
	break;
	
	case 'change_password':
	{
		assign('mode','change_password');
	}
	break;
	
	case 'block_users':
	{
		assign('mode','block_users');
	}
	break;
	
	case 'subscriptions':
	{
		//Removing subscription
		if(isset($_GET['delete_subs']))
		{
			$sid = mysql_clean($_GET['delete_subs']);
			$userquery->unsubscribe_user($sid);
		}
		assign('mode','subs');
		assign('subs',$userquery->get_user_subscriptions(userid()));
	}
	break;
	
	default:
	{
		assign('on','account');
		assign('mode','profile_settings');
	}
}


$udetails = $userquery->get_user_details(userid());
$profile = $userquery->get_user_profile($udetails['userid']);
$user_profile = array_merge($udetails,$profile);
//pr($Cbucket->header_files);
assign('user',$udetails);
assign('p',$user_profile);
subtitle(lang("user_manage_my_account"));
template_files('edit_account.html');
display_it();
?>