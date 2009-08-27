<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_SESSION['username'];

	$url = mysql_clean($_GET['url']);
	include('group_inc.php');
	$details = $groups->GetDetails($url);
	$group 	= 	$details['group_id'];
	$user 	= 	$_SESSION['username'];
	if(empty($user)){
	$user 	= 	$_COOKIE['username'];
	}
	$MustJoin = 'No';
	include('group_check.php');
	
Assign('groups',$details);

//Removing A Video
if(isset($_POST['remove'])){
	$msg = $groups->RemoveVideos($group);
}
//Approve Videos
if(isset($_POST['approve'])){
	$msg = $groups->ApproveVideos($group);
}

//Getting Videos List
	$limit  = 5;
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";


	@$show=mysql_clean($_GET['show']);
	Assign('show',$show);
	if($show != 'unapproved'){
		$filter = " AND active = 'yes' ";
	}else{
		$filter = " AND active = 'no' ";
	}
	
		
	$orderby = 	@$orders[$order];
	$sql = "SELECT * FROM group_members WHERE group_id='".$group."'  AND active = 'yes' $query_limit";
	$sql_p = "SELECT * FROM group_members WHERE group_id='".$group."'  AND active = 'yes' ";

	$data 			= $db->Execute($sql);
	$users			= $data->getrows();
	$total_users	= $data->recordcount()+0;
	
	for($id=0;$id<$total_users;$id++){
		$query 	= mysql_query("SELECT * FROM users WHERE username='".$users[$id]['username']."'");
		$data 	= mysql_fetch_array($query);
		$users[$id]['username'] = $data['username'];
		$users[$id]['profile_hits'] = $data['profile_hits'];
		$users[$id]['avatar'] = $data['avatar'];
		$users[$id]['total_videos']	= $data['total_videos'];
	}
	Assign('total_users',$total_users);
	Assign('users',$users);
	
//Pagination
	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);
	
@$show_pages = ShowPagination($pages,$page,'?url='.$url.'&order='.$order);
Assign('show_pages',$show_pages);

	
Assign('link','?url='.$url.'&order='.@$order);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	
Assign('subtitle',$details['group_name'].' - Members');
@Assign('msg',$msg);
@Assign('show_group',$show_group);
Template('header.html');
Template('message.html');
Template('group_header.html');
Template('view_group_members.html');
Template('footer.html');

?>