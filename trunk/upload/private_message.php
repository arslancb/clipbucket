<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'private_message');

require 'includes/config.inc.php';

//Adding JS Scroll
add_js('jquery_plugs/compressed/jquery.scrollTo-min.js');

$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

//Deleting Multple Message
if(isset($_POST['delete_pm']))
{
	if($mode=='inbox' || $mode =='notification')
		$box = 'in';
	else
		$box = 'out';
		
	$total = count($_POST['msg_id']);
	for($pms=0;$pms<$total;$pms++)
	{
		if(!empty($_POST['msg_id'][$pms]))
		{
			$cbpm->delete_msg($_POST['msg_id'][$pms],userid(),$box);
		}
		
		$eh->flush();
		e(lang('private_messags_deleted'),'m');
	}
}



switch($mode)
{
	case 'inbox':
	default:
	{
		
		assign('mode','inbox');
		
		//Deleting Message
		if($_GET['delete_mid'])
		{
			$mid = mysql_clean($_GET['delete_mid']);
			$cbpm->delete_msg($mid,userid());
		}
		
		//Getting Message
		if($_GET['mid'])
		{
			$mid = mysql_clean($_GET['mid']);
			$pr_msg = $cbpm->get_inbox_message($mid,userid());
			if($pr_msg['message_status'] =='unread')
				 $cbpm->set_message_status($mid,'read');
			assign('pr_msg',$pr_msg);
		}
		
		//Get User Messages
		assign('user_msgs',$cbpm->get_user_inbox_messages(userid()));
		
		subtitle(lang("com_my_inbox"));
	}
	break;
	
	case 'sent':
	{
		assign('mode','sent');
		
		
		//Deleting Message
		if($_GET['delete_mid'])
		{
			$mid = mysql_clean($_GET['delete_mid']);
			$cbpm->delete_msg($mid,userid(),'out');
		}
		
		//Getting Message
		if($_GET['mid'])
		{
			$mid = mysql_clean($_GET['mid']);
			assign('pr_msg',$cbpm->get_outbox_message($mid,userid()));
		}
		
		//Get User Messages
		assign('user_msgs',$cbpm->get_user_outbox_messages(userid()));
		
		subtitle(lang("user_sent_box"));
	}
	
	break;
	
	case 'notification':
	{
		assign('mode','notification');
		
		//Deleting Message
		if($_GET['delete_mid'])
		{
			$mid = mysql_clean($_GET['delete_mid']);
			$cbpm->delete_msg($mid,userid());
		}
		
		//Getting Message
		if($_GET['mid'])
		{
			$mid = mysql_clean($_GET['mid']);
			assign('pr_msg',$cbpm->get_inbox_message($mid,userid()));
		}
		
		//Get User Messages
		assign('user_msgs',$cbpm->get_user_notification_messages(userid()));
		
		subtitle(lang("my_notifications"));
	}
	break;
	
	case 'new_msg':
	case 'compose':
	{
		assign('mode','new_msg');
		
		//Checkking If reply
		if($_GET['reply']!='')
		{
			$mid = mysql_clean($_GET['reply']);
			if(!isset($_POST['send_message']) && $cbpm->is_reply($mid,userid()))
			{
				$reply_msg = $cbpm->get_inbox_message($mid,userid());
				$_POST['to'] = $userquery->get_user_field_only($reply_msg['message_from'],'username');
				$_POST['subj'] = "Re:".$reply_msg['message_subject'];
			}
		}
		
		//sending message
		if(isset($_POST['send_message']))
		{
			$array = $_POST;
			$array['reply_to'] = mysql_clean($_GET['reply']);
			$array['is_pm'] = true;
			$array['from'] = userid();
			$cbpm->send_pm($array);
			
			if(!error())
				$_POST = '';
		}	
		
		subtitle(lang("title_crt_new_msg"));
	}
	
}

template_files('private_message.html');
display_it();
?>