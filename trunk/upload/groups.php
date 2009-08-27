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
subtitle('groups');
				
//Getting List Of Categories
	$sql = "SELECT * FROM category ORDER BY category_name";
	$data = $db->Execute($sql);
	$total_data = $data->recordcount() + 0;
	$category = $data->getrows();
	Assign('category',$category);


//Listing Videos
	$limit  = GLISTPP;
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	
	//Getting Category
			@$cat = mysql_clean($_GET['ct']);
			if(empty($cat) || !is_numeric($cat)){
			$cat_query = " group_category <>'' ";
			}else{
			$cat_query = "group_category ='".$cat."' ";
			}
				
		$cur_cat = $myquery->GetCategory($cat);
		Assign('cur_cat',$cur_cat);
		
	//Getting Order
	@$order = mysql_clean($_GET['order']);
		if(empty($order)){
			$order = 'all';
			}	
			Assign('order',$order);
			$orders = array(
				'all'		=> '',
				'mr'		=> 'ORDER BY date_added DESC',
				'mv'		=> 'ORDER BY total_videos DESC',
				'mt'		=> 'ORDER BY total_topics DESC',
				'mm'		=> 'ORDER BY total_members DESC',
				'fr'		=> "AND featured='yes'",
				'ra'		=> 'ORDER by RAND()'
				);
				
	$group_type = " AND (group_type ='0' OR group_type = '1' )";
	$orderby = 	$orders[$order];	
	$sql = "SELECT * FROM groups WHERE $cat_query  $group_type $orderby $query_limit";
	$sql_p = "SELECT * FROM groups WHERE $cat_query  $group_type $orderby";
	$groups_data = $db->Execute($sql);
	$total_groups = $groups_data->recordcount() + 0;
	$groups = $groups_data->getrows();
		
	Assign('groups',$groups);
	Assign('ct','&amp;ct='.$cat);
	
//Pagination
$link = '&amp;ct='.$cat.'&amp;order='.$order;
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
	

Template('header.html');
Template('message.html');
Template('groups.html');
Template('footer.html');
?>