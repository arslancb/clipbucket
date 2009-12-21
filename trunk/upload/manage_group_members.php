<?php
/**
 * @ author : Arslan Hassan
 * @ file : Mansge group members
 * @ date updated : March 12 2009
 * @ license : CBLA
 */

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_SESSION['username'];

$url = mysql_clean($_GET['url']);
//Updating Group
if(isset($_POST['update'])){
	$msg = $cbgroup->UpdateGroup();
}

		include('group_inc.php');

		$details = $cbgroup->GetDetails($url);
		$group 	= 	$details['group_id'];
		$user 	= 	$_SESSION['username'];
		if(empty($user)){
		$user 	= 	$_COOKIE['username'];
		}
		if($details['username'] != $user){
			$msg = $LANG['grp_owner_err1'];
			$show_group = 'No';
		}
		if($user == $details['username']){
			Assign('owner','yes');
		}
		//Chceking Logged in user is group user or not
		if(!$cbgroup->is_joined($_SESSION['username'],$group)){
			Assign('join','yes');
		}else{
			Assign('join','no');
		}
		

//Removing A Video
if(isset($_POST['remove'])){
	$msg = $cbgroup->RemoveMembers($group);
}
//Approve Videos
if(isset($_POST['approve'])){
	$msg = $cbgroup->ApproveMembers($group);
}

//Getting Videos List
	$limit  = CLISTPP;
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
	if($show != 'inactive'){
		$filter = " AND active = 'yes' ";
	}else{
		$filter = " AND active = 'no' ";
	}
	
	$sql = "SELECT * FROM group_members WHERE group_id='".$group."' $filter  $query_limit";
	$sql_p = "SELECT * FROM group_members WHERE group_id='".$group."' $filter";

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

@$show_pages = ShowPagination($pages,$page,'order='.$order);
Assign('show_pages',$show_pages);

@Assign('link','order='.$order);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	
Assign('groups',$details);
subtitle('manage_video');
@Assign('msg',$msg);
@Assign('show_group',$show_group);
Template('header.html');
Template('message.html');
if(@$show_group !='No'){
Template('group_header.html');
}
Template('manage_group_members.html');
Template('footer.html');

?>