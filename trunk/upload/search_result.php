<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author   : ArslanHassan																		|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();

@$type = mysql_clean($_GET['type']);
if(empty($type) || $type !='videos' && $type !='groups' && $type !='channels'){
$type = 'videos';
}

//Assigning Category List
	$sql = "SELECT * FROM category ORDER BY category_name";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	
	
//Form Processing
$key_query = mysql_clean($_GET['query']);
if($key_query=='Array'){
$key_query = "Search Safely";
}
Assign('query',$key_query);

	$limit  = SLISTPP;	
	Assign('limit',$limit);
	@$page   = mysql_clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	
	//Getting Sort
	@$sort = mysql_clean($_GET['sort']);
	switch($sort){
	case 'asc';
	$sort_order = "ASC";
	break;
	
	case 'desc';
	$sort_order = "DESC";
	break;
	
	default:
	$sort_order = "DESC";
	}
	
	//Getting Order
	@$order = mysql_clean($_GET['order']);
	if(empty($order)){
	$order = 'all';
	}	
	Assign('order',$order);
	$orders = array(
	'all'		=> 'ORDER BY date_added '.$sort_order,
	'mr'		=> 'ORDER BY date_added '.$sort_order,
	'mv'		=> 'ORDER BY views '.$sort_order,
	'fr'		=> "AND featured='yes'",
	'ra'		=> 'ORDER by RAND()'

	);
	$orderby = 	$orders[$order];	
	Assign('default_order',$order);
	Assign('default_type',$type);
	
	//Getting Category
			@$cat = mysql_clean($_GET['ct']);
			if($cat !=="" && $cat!='all'){
				$cat_query = "AND ( category01 ='".$cat."' OR category02 ='".$cat."' OR category03 ='".$cat."' )";
				}else{
				$cat = 'all';
				$cat_query = "AND ( title <>'' )";
				}
		
		Assign('cur_cat',$cat);
		
	//Setting Queries for videos
	$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
	if($type == 'videos'){
	$sql 			= "SELECT * FROM video WHERE broadcast='public' AND (title like '%$key_query%' OR tags like '%$key_query%') AND $query_param $cat_query $orderby $query_limit";
	$sql_p			= "SELECT * FROM video WHERE broadcast='public' AND (title like '%$key_query%' OR tags like '%$key_query%') AND $query_param  ";
	$data			= $db->Execute($sql);
	$videos			= $data->getrows();
	$total_videos	= $data->recordcount();
	
	for($id=0;$id<$total_videos;$id++){
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	$videos[$id]['duration'] = SetTime($videos[$id]['duration']);
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false,'novote');
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}
	Assign('videos',$videos);
	}
	
	//Setting quesies for Channels
	//Getting Order
	@$order = mysql_clean($_GET['order']);
	if(empty($order)){
	$order = 'all';
	}	
	Assign('order',$order);
	$orders = array(
	'all'		=> 'ORDER BY doj '.$sort_order,
	'mr'		=> 'ORDER BY doj '.$sort_order,
	'mv'		=> 'ORDER BY profile_hits '.$sort_order,
	'fr'		=> "AND featured='yes'",
	'ra'		=> 'ORDER by RAND()'

	);
	$orderby = 	$orders[$order];
	
				
	if($type == 'channels'){
		$sql 			= "SELECT * FROM users WHERE channel_title like '%$key_query%' OR channel_des like '%$key_query%' $orderby $query_limit";
		$sql_p			= "SELECT * FROM users WHERE channel_title like '%$key_query%' OR channel_des like '%$key_query%' ";
		
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
	}
	
	//Setting quesies for Groups
	//Getting Order
	@$order = mysql_clean($_GET['order']);
	if(empty($order)){
	$order = 'all';
	}	
	Assign('order',$order);
	$orders = array(
	'all'		=> 'ORDER BY date_added '.$sort_order,
	'mr'		=> 'ORDER BY date_added '.$sort_order,
	'mv'		=> 'ORDER BY date_added '.$sort_order,
	'fr'		=> "AND featured='yes'",
	'ra'		=> 'ORDER by RAND()'

	);
	$orderby = 	$orders[$order];
	
	
		//Getting Category
			@$cat = mysql_clean($_GET['ct']);
			if($cat !=="" && $cat!='all'){
				$cat_query = "AND group_category'".$cat."'";
				}else{
				$cat = 'all';
				$cat_query = "AND ( group_name <>'' )";
				}
	if($type=='groups'){
	$sql = "SELECT * FROM groups WHERE group_type <>'2' AND (group_name like '%$key_query%' OR group_tags like '%$key_query%' OR group_url like '%$key_query%') $cat_query $orderby  $query_limit";
	$sql_p = "SELECT * FROM groups WHERE group_type <>'2' AND (group_name like '%$key_query%' OR group_tags like '%$key_query%' OR group_url like '%$key_query%')";
	
	$groups_data = $db->Execute($sql);
	$total_groups = $groups_data->recordcount() + 0;
	$groups = $groups_data->getrows();
		
	Assign('groups',$groups);
	}
	

$link = '?query='.$key_query.'&type='.$type.'&sort='.$sort.'&ct='.$cat.'&order='.$order;

Assign('query',$key_query);
Assign('link',$link);
Assign('type',$type);

	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	$records = $total_rows/$limit;
	$pages = round($records+0.49,0);
	
$show_pages = ShowPagination($pages,$page,$link);
Assign('show_pages',$show_pages);
	
Assign('subtitle','Search - '.$key_query);
Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);

@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('search_result.html');
Template('footer.html');
?>