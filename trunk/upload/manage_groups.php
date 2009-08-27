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

//Leaving Groups
if(isset($_POST['leave'])){
	$query = mysql_query("SELECT * FROM group_members WHERE username = '".$user."'");
		while($data=mysql_fetch_array($query)){
			if($_POST[$data['group_id']] == 'yes'){
			$groups->LeaveGroup($user,$data['group_id']);
			}
		}
	$msg = $LANG['grp_rmv_msg'];
}

//Remove Groups
if(isset($_POST['remove'])){
	$query = mysql_query("SELECT * FROM groups WHERE username='".$user."'");
		while($data=mysql_fetch_array($query)){
			if($_POST[$data['group_id']] == 'yes'){
			$groups->DeleteGroup($data['group_id']);
			}
		}
	$msg = $LANG['grp_del_msg'];
}
			

//Getting Videos List
	$limit = GLISTPP;
	Assign('limit',$limit);
	$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	
	//Getting Order
	$show = mysql_clean($_GET['show']);
	if($show == 'owned'){
	Assign('show','Owned');
	$sql 			= "SELECT * FROM groups WHERE username='".$user."' $orderby $query_limit ";
	$sql_p 			= "SELECT * FROM groups WHERE username='".$user."' ";
	}else{
	Assign('show','Joined');
	$sql 			= "SELECT * FROM group_members 	WHERE username='".$user."' $orderby $query_limit ";
	$sql_p 			= "SELECT * FROM group_members 	WHERE username='".$user."' ";
	}
	
	$data 			= $db->Execute($sql);
	$groups			= $data->getrows();
	$total_groups	= $data->recordcount()+0;
	
	for($id=0;$id<$total_groups;$id++){
	$query = mysql_query("SELECT * FROM groups WHERE group_id='".$groups[$id]['group_id']."'");
	$group_details = mysql_fetch_array($query);
		$groups[$id]['group_name'] 	= $group_details['group_name'];
		$groups[$id]['dateadded'] 	= $group_details['dateadded'];
		$groups[$id]['username'] 	= $group_details['username'];
		$groups[$id]['group_url'] 	= $group_details['group_url'];
		$groups[$id]['group_thumb'] = $group_details['group_thumb'];
		$groups[$id]['approved'] 	= $group_details['approved'];
	}
	
	Assign('groups',$groups);
	
//Pagination
	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);

$show_pages = ShowPagination($pages,$page,'order='.$order);
Assign('show_pages',$show_pages);

Assign('link','order='.$order);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	

subtitle('manage_video');
Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('manage_groups.html');
Template('footer.html');
?>