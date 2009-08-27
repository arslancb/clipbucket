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
	$limit  = VLISTPP;
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
		$filter = " AND approved = 'yes' ";
	}else{
		$filter = " AND approved = 'no' ";
	}
	
		
	@$orderby = 	$orders[$order];
	$sql = "SELECT * FROM group_videos WHERE group_id='".$group."' AND approved = 'yes' $query_limit";
	$sql_p = "SELECT * FROM group_videos WHERE group_id='".$group."' AND approved = 'yes'";

	$data 			= $db->Execute($sql);
	$videos			= $data->getrows();
	$total_videos	= $data->recordcount()+0;
	
	for($id=0;$id<$total_videos;$id++){
	$data 	= $myquery->GetVideDetails($videos[$id]['videokey']);
	$flv 	= $data['flv'];
	$videos[$id]['thumb'] 			= GetThumb($flv);
	$videos[$id]['title'] 			= $data['title'];
	$videos[$id]['duration'] 		= SetTime($data['duration']);
	$videos[$id]['views'] 			= $data['views'];
	$videos[$id]['show_rating'] 	= pullRating($data['videoid'],false,false,false,'novote');
	$videos[$id]['url'] 			= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
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
	
@$show_pages = ShowPagination($pages,$page,'?url='.$url.'&order='.$order);
Assign('show_pages',$show_pages);

	
Assign('link','?url='.$url.'&order='.@$order);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	

subtitle('manage_video');
@Assign('msg',$msg);
@Assign('show_group',$show_group);
Template('header.html');
Template('message.html');
Template('group_header.html');
Template('view_group_videos.html');
Template('footer.html');

?>