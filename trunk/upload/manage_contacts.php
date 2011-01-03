<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan										
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ******************************************************************
*/

define("THIS_PAGE",'manage_channels');
define("PARENT_PAGE","channels");

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

switch($mode)
{
	case 'request':
	case 'normal':
	case 'view':
	case 'manage':
	default:
	{
		if($mode="request" && isset($_GET['confirm']))
		{
			$confirm = mysql_clean($_GET['confirm']);
			$userquery->confirm_request($confirm);
		}
		
		if($mode="delete" && isset($_GET['userid']))
		{
			$userid = mysql_clean($_GET['userid']);
			$userquery->remove_contact($userid);
		}
		
		assign("mode","manage");
		
	}
	break;
}

subtitle(lang("user_manage_contacts"));
template_files('manage_contacts.html');
display_it();
?>