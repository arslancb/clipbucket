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
Assign('user',$user);
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
//Listing Videos

	$limit  = VLISTPP;	
	Assign('limit',$limit);
	$page   = clean(@$_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	$orderby	  = " ORDER BY date_added DESC";

	$sql 	= "SELECT * FROM video WHERE username='".$user."'  AND $query_param $orderby $query_limit";
	$sql_p 	= "SELECT * FROM video WHERE username='".$user."'  AND $query_param ";
	
	$vdo_data = $db->Execute($sql);
	$total_vdo = $vdo_data->recordcount() + 0;
	$videos = $vdo_data->getrows();
	
	for($id=0;$id<$total_vdo;$id++){
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	$videos[$id]['duration'] 	= SetTime($videos[$id]['duration']);
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false);
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}

	Assign('videos',$videos);
	//Pagination
	$link = '?user='.$user;
	Assign('link',$link);
	$query= mysql_query($sql_p);
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

Assign('subtitle',$user."'s Videos");

Assign('msg',@$msg);
Template('header.html');
Template('message.html');	
Template('user_videos.html');
Template('footer.html');
?>