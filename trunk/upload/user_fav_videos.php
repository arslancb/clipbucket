<?php
/* 
 ************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.		|
 | @ Author : ArslanHassan												|
 | @ Software : ClipBucket , © PHPBucket.com							|
 ************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();

$user = mysql_clean($_GET['user']);
$user_data = $userquery->GetUserData_username($user);
Assign('user',$user_data);

//Listing Contacts
	$limit  = VLISTPP;	
	Assign('limit',$limit);
	$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	$orderby	  = " ORDER BY date_added DESC";

	$sql 	= "SELECT * FROM video_favourites WHERE userid='".$user_data['userid']."'   $orderby $query_limit";
	$sql_p 	= "SELECT * FROM video_favourites WHERE userid='".$user_data['userid']."'   ";
	
	$video_Data = $db->Execute($sql);
	$total_videos = $video_Data->recordcount() + 0;
	$videos = $video_Data->getrows();
	
	for($id=0;$id<$total_videos;$id++){
	$videos_details = $myquery->GetVideoDetails($videos[$id]['videoid']);
	$flv =	$videos_details['flv'];
	$videos[$id]['thumb'] 		= GetThumb($flv);
	$videos[$id]['show_rating'] = pullRating($videos_details['videoid'],false,false,false);
	$videos[$id]['title'] 		= $videos_details['title'];
	$videos[$id]['description'] = $videos_details['description'];
	$videos[$id]['videokey'] 	= $videos_details['videokey'];
	$videos[$id]['views'] 		= $videos_details['views'];
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
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

$show_pages = ShowPagination($pages,$page,'?user='.$user);
Assign('show_pages',$show_pages);
	
Assign('link','?user='.$user);	
Assign('pages',$pages+1);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
Assign('subtitle',$user.$LANG['title_usr_fav_vids']);
Template('header.html');
Template('message.html');	
Template('user_fav_videos.html');
Template('footer.html');
?>