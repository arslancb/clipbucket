<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();

$user = mysql_clean($_GET['user']);
$user_data = $userquery->GetUserData_username($user);
Assign('user',$user_data);

//Listing Contacts
	$limit  = CLISTPP;	
	Assign('limit',$limit);
	$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	$orderby	  = " ORDER BY friend_username";

	$sql 	= "SELECT * FROM contacts WHERE username='".$user."' $orderby $query_limit";
	$sql_p 	= "SELECT * FROM contacts WHERE username='".$user."'";
	
	$contacts_data = $db->Execute($sql);
	$total_contacts = $contacts_data->recordcount() + 0;
	$contacts = $contacts_data->getrows();
	
	for($id=0;$id<$total_contacts;$id++){
	$contact_details = $userquery->GetUserData_username($contacts[$id]['friend_username']);
	$contacts[$id]['avatar'] = $contact_details['avatar'];
	$contacts[$id]['views'] = $contact_details['profile_hits'];
	$contacts[$id]['videos'] = $contact_details['total_videos'];
	$contacts[$id]['comments'] = $contact_details['total_comments'];
	$contacts[$id]['doj'] = $contact_details['doj'];
	}

	Assign('contacts',$contacts);
	
	
	
//Pagination
	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	//$all_pages[0]['page'] = $page_id;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);

$show_pages = ShowPagination($pages,$page,'?user='.$user);
Assign('show_pages',$show_pages);

Assign('link','?user='.$user);	
Assign('pages',$pages+1);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
Assign('subtitle',$user.$LANG['title_usr_contact']);
Template('header.html');
Template('message.html');	
Template('user_contacts.html');
Template('footer.html');
?>