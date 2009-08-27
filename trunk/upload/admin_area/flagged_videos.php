<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Function Used To Remove Flag

if(isset($_GET['remove_flags'])){
	$video = mysql_clean($_GET['remove_flags']);
	if($myquery->VideoExists($video)){
		$msg[] = $myquery->DeleteFlag($video);
	}else{
		$msg[] = $LANG['class_vdo_del_err'];
	}
}

//Activate / Deactivate

if(isset($_GET['activate'])){
	$video = mysql_clean($_GET['activate']);
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->ActivateVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}
if(isset($_GET['deactivate'])){
	$video = mysql_clean($_GET['deactivate']);
	if($myquery->DeActivateVideo($video)){
	$msg[] = $myquery->DeActivateVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}

//Delete Video
if(isset($_GET['delete_video'])){
	$video = mysql_clean($_GET['delete_video']);
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->DeleteVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}
//Getting List From Flaggeed Videos

	$limit  = 15;	
	Assign('limit',$limit);
	$page   = mysql_clean(@$_GET['page']);
	Assign('limit',$limit);
	if(empty($page) || $page == 0){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	
	$query_limit  = "limit $from,$limit";

	$query 			= "SELECT videoid, count(*) AS flags FROM flagged_videos GROUP BY videoid ORDER BY flags DESC $query_limit";
	$sql_p			= "SELECT videoid, count(*) AS flags FROM flagged_videos GROUP BY videoid ORDER BY flags DESC ";
	$data			= $db->Execute($query);
	$videos			= $data->getrows();
	$total_videos	= $data->recordcount()+0;

	for($id=0;$id<$total_videos;$id++){
	$vdo_data = $myquery->GetVideoDetails($videos[$id]['videoid']);
	$title	  = $vdo_data['title'];
	$videokey = $vdo_data['videokey'];
	$active   = $vdo_data['active'];
	$username = $vdo_data['username'];
	$type 	  = $vdo_data['broadcast'];
	$videos[$id]['title'] 		= $title;
	$videos[$id]['videokey'] 	= $videokey;
	$videos[$id]['active'] 		= $active;
	$videos[$id]['username'] 	= $username;
	$videos[$id]['broadcast'] 	= $type;
	$$vdo_data[$id]				= $vdo_data;
	}
	
	Assign('videos',$videos);

//Pagination 

	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	//$all_pages[0]['page'] = $page_id;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);
	
	Assign('pages',$pages+1);
	Assign('cur_page',$page);
	Assign('nextpage',$page+1);
	Assign('prepage',$page-1);
	Assign('total_pages',$page_id);

Assign('msg', @$msg);	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('flagged_videos.html');
Template('footer.html');
?>