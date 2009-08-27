<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.									|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_SESSION['username'];

//Removing A Video
if(!empty($_POST['delete_videoid']) && !isset($_POST['delete_videos'])){
$videoid = $_POST['delete_videoid'];
$msg = $myquery->DeleteUserVideo($videoid ,$_SESSION['username']);
}

//Removing Multiple Videos
if(isset($_POST['delete_videos'])){
$query = mysql_query("SELECT * FROM video WHERE username = '".$_SESSION['username']."' ");
while($data = mysql_fetch_array($query)){
	if(@$_POST[$data['videokey']] == 'yes'){
	$myquery->DeleteUserVideo($data['videoid'] ,$_SESSION['username']);
	}	
}
$msg = $LANG['vdo_del_selected'];
}						
						
//Getting Videos List
	$limit = VLISTPP;
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	
	//Getting Order
	$orders = array(
	'mr'	=> 'ORDER by date_added DESC',
	'fr'	=> "AND featured = 'yes'",
	'mv'	=> "ORDER by views DESC"
	);
	@$order = mysql_clean($_GET['order']);
	@$orderby = $orders[$order];
	if(empty($orderby)){
		$orderby = "ORDER BY date_added DESC";
	}

	$sql 			= "SELECT * FROM video WHERE username='".$user."' $orderby $query_limit ";
	$sql_p 			= "SELECT * FROM video WHERE username='".$user."' ";
	if($order=='fr'){
	$sql_p 			= "SELECT * FROM video WHERE username='".$user."' AND featured = 'yes'";
	}
	$data 			= $db->Execute($sql);
	$videos			= $data->getrows();
	$total_videos	= $data->recordcount()+0;
	
	for($id=0;$id<$total_videos;$id++){
	$query=mysql_query("SELECT * FROM video_detail WHERE flv='".$videos[$id]['flv']."'");
	$data = mysql_fetch_array($query);
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	$videos[$id]['duration'] 	= SetTime($videos[$id]['duration']);
	$videos[$id]['raw_file'] 	= $data['original'] ? $data['original'] : 'No Raw File';
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false);
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}
	
	Assign('videos',$videos);
	Assign('total_videos',$total_videos);
//Pagination
	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);

$show_pages = ShowPagination($pages,$page,'?order='.$order);
Assign('show_pages',$show_pages);

Assign('link','?order='.$order);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);
	

subtitle('manage_video');
@Assign('msg',$msg);
if(@$_GET['show']!='videos'){
Template('header.html');
Template('message.html');
Template('manage_videos.html');
Template('footer.html');
}else{
Assign('full_view','no');
Template('manage_videos.html');
}
?>