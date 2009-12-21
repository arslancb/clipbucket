<?php

/**
 * @ author : Arslan Hassan
 * @ file : Mansge group videos
 * @ date updated : March 12 2009
 * @ license : CBLA
 */

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_SESSION['username'];

$manage_vids = TRUE;
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
		
Assign('groups',$details);

//Removing A Video
if(isset($_POST['remove'])){
	$msg = $cbgroup->RemoveVideos($group);
}
//Approve Videos
if(isset($_POST['approve'])){
	$msg = $cbgroup->ApproveVideos($group);
}

//Getting Videos List
	$limit  = VLISTPP;
	Assign('limit',$limit);
	$page   = clean(@$_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";


	$show=mysql_clean(@$_GET['show']);
	Assign('show',$show);
	if($show != 'unapproved'){
		$filter = " AND approved = 'yes' ";
	}else{
		$filter = " AND approved = 'no' ";
	}
	
	$orderby = 	@$orders[$order];
	$sql = "SELECT * FROM group_videos WHERE group_id='".$group."' $filter  $query_limit";
	$sql_p = "SELECT * FROM group_videos WHERE group_id='".$group."' $filter";

	$data 			= $db->Execute($sql);
	$videos			= $data->getrows();
	$total_videos	= $data->recordcount()+0;
	
	for($id=0;$id<$total_videos;$id++){
	 $videos[$id] = $myquery->GetVideDetails($videos[$id]['videokey']);
	}
	Assign('total_videos',$total_videos);
	Assign('videos',$videos);
	
//Pagination
	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);

Assign('link','order='.@$order);
$show_pages = ShowPagination($pages,$page,'order='.@$order);
Assign('show_pages',$show_pages);

Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	

subtitle('manage_video');
Assign('msg',@$msg);
Assign('show_group',@$show_group);
Template('header.html');
Template('message.html');
if(@$show_group !='No'){
Template('group_header.html');
}
Template('manage_group_videos.html');
Template('footer.html');

?>