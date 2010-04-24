<?php

/**
 * This file is used to update
 * Comment
 * Author : Arslan Hassan
 * Since : Jan 02 2009
 */
 
 
require'../includes/admin_config.php';
$userquery->admin_login_check();

$mode = $_POST['mode'];
switch($mode)
{
	case 'add_note':
	{
		$value = $_POST['note'];
		$myquery->insert_note($value);
		$array['note'] = nl2br($value);
		$array['id'] = $db->insert_id();
		
		echo json_encode($array);
	}
	break;
	case 'delete_note':
	{
		$id = mysql_clean($_POST['id']);
		$myquery->delete_note($id);
	}
	break;
	
	case 'delete_comment':
	{
		$type = $_POST['type'];
		switch($type)
		{
			case 'v':
			case 'video':
			default:
			{
				$cid = mysql_clean($_POST['cid']);
				$type_id = $myquery->delete_comment($cid);
				$cbvid->update_comments_count($type_id);
			}
			break;
			case 'u':
			case 'c':
			{
				$cid = mysql_clean($_POST['cid']);
				$type_id = $myquery->delete_comment($cid);
				$userquery->update_comments_count($type_id);
			}
			break;
			case 't':
			case 'topic':
			{
				$cid = mysql_clean($_POST['cid']);
				$type_id = $myquery->delete_comment($cid);
				$cbgroup->update_comments_count($type_id);
			}
			break;
			
		}
		if(msg())
		{
			$msg = msg_list();
			$msg = $msg[0];
		}
		if(error())
		{
			$err = error_list();
			$err = $err[0];
		}
		
		$ajax['msg'] = $msg;
		$ajax['err'] = $err;
		
		echo json_encode($ajax);
	}
	break;
	
	case 'spam_comment':
	{
		$cid = mysql_clean($_POST['cid']);

			
		$rating = $myquery->spam_comment($cid);
		if(msg())
		{
			$msg = msg_list();
			$msg = $msg[0];
		}
		if(error())
		{
			$err = error_list();
			$err = $err[0];
		}
		
		$ajax['msg'] = $msg;
		$ajax['err'] = $err;
		
		echo json_encode($ajax);
	}
	break;
	
	case 'remove_spam':
	{
		$cid = mysql_clean($_POST['cid']);

			
		$rating = $myquery->remove_spam($cid);
		if(msg())
		{
			$msg = msg_list();
			$msg = $msg[0];
		}
		if(error())
		{
			$err = error_list();
			$err = $err[0];
		}
		
		$ajax['msg'] = $msg;
		$ajax['err'] = $err;
		
		echo json_encode($ajax);
	}
	break;	
}
?>
