<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan										
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ******************************************************************
*/

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

////Getting Videos List
//	$limit = CLISTPP;
//	Assign('limit',$limit);
//	$page   = clean(@$_GET['page']);
//	if(empty($page) || $page == 0 || !is_numeric($page)){
//	$page   = 1;
//	}
//	$from 	= $page-1;
//	$from 	= $from*$limit;
//	$query_limit  = "limit $from,$limit";
//
//	$sql 				= "SELECT * FROM contacts  WHERE username='".$user."' ORDER BY date_added DESC $query_limit";
//	$sql_p 				= "SELECT * FROM contacts  WHERE username='".$user."' ORDER BY date_added DESC ";
//	$data 				= $db->Execute($sql);
//	$contact			= $data->getrows();
//	$total_contact		= $data->recordcount()+0;
//	
//	for($id=0;$id<$total_contact;$id++){
//	$udata					= $userquery->GetUserData_username($contact[$id]['friend_username']);
//	$contact[$id]['avatar'] = $udata['avatar'];
//	}
//	
//	Assign('contacts',$contact);
//	
////Pagination
//	$query = mysql_query($sql_p);
//	Assign('grand_total',mysql_num_rows($query));
//	$total_rows = mysql_num_rows($query);
//	$page_id=1;
//	$id = 1;
//	//$all_pages[0]['page'] = $page_id;
//	$records = $total_rows/$limit;
//	$pages = round($records+0.49,0);
//
//	
//$show_pages = ShowPagination($pages,$page,@$link);
//Assign('show_pages',$show_pages);
//		
//Assign('pages',$pages);
//Assign('cur_page',$page);
//Assign('nextpage',$page+1);
//Assign('prepage',$page-1);
//Assign('total_pages',$page_id);
//subtitle('contacts');
//Assign('msg',@$msg);
//Template('header.html');
//Template('message.html');
//Template('manage_contacts.html');
//Template('footer.html');


template_files('manage_contacts.html');
display_it();
?>