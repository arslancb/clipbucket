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
subtitle('channels');

//Getting Order
	@$order = $_GET['order'];
	if(empty($order) || is_array($_GET['order'])){
		$order = "ra";
		}
$orders = array(
		"mv" 		=> "ORDER BY profile_hits DESC",
		"fr"		=> "WHERE featured='yes'",
		"ra"		=> "ORDER BY doj DESC",
		"vd"		=> "ORDER BY total_videos DESC",
		"ct"		=> "ORDER BY total_comments DESC"
				);
				
@$show_order = array(
		"mv" 		=> $LANG['mostly_viewed'],
		"fr"		=> $LANG['featured'],
		"ra"		=> $LANG['recently_added'],
		"vd"		=> $LANG['most_videos'],
		"ct"		=> $LANG['most_comments']
				);
Assign('show_order',$show_order[$order]);
				
//Setting Up Queries
				
	$limit  = CLISTPP;	
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page) || $page < 0){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	
	$orderby = 	$orders[$order];

	//$sql 	= "SELECT * FROM users WHERE usr_status='Ok' $orderby $query_limit";
	//$sql_p	= "SELECT * FROM users WHERE usr_status='Ok' $orderby";
	
	$sql 	= "SELECT * FROM users  $orderby $query_limit";
	$sql_p	= "SELECT * FROM users  $orderby ";

	$user_data = $db->Execute($sql);
	$total_users = $user_data->recordcount() + 0;
	$users = $user_data->getrows();
	
	for($id=0;$id<$total_users;$id++){
	
	$sql = "Select * FROM channel_comments WHERE channel_user='".$users[$id]['username']."'";
	$rs = $db->Execute($sql);
	$users[$id]['total_comments'] = $rs->recordcount() + 0;
	
	$sql = "Select * FROM video WHERE username='".$users[$id]['username']."'";
	$rs = $db->Execute($sql);
	$users[$id]['total_videos'] = $rs->recordcount() + 0;
	
	}
	
	Assign('users',$users);
	
//Pagination
$link = '?order='.$order;
Assign('link',$link);

	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	//$all_pages[0]['page'] = $page_id;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);
	
	
$show_pages = ShowPagination($pages,$page,$link);
Assign('show_pages',$show_pages);
	
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);

@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('channels.html');
Template('footer.html');

?>