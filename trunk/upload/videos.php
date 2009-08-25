<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.									|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$pages->page_redir();
subtitle('videos');

    $cat_id = "";
    $featured = "";
    $orderby = "";
    $query_limit = "";

$myquery->getVideoList(array('limit'=>'nolimit','featured'=>'yes','order'=>'date_added DESC'));

//Getting List Of Categories
	$sql = "SELECT * FROM category ORDER BY category_name";
	$data = $db->Execute($sql);
	$total_data = $data->recordcount() + 0;
	$category = $data->getrows();
	Assign('category',$category);

//Listing Videos
	$limit  = VLISTPP;
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "$from,$limit";	
	//Getting Category
		@$cat = mysql_clean($_GET['ct']);
		if(!empty($cat) && $cat !='all'){
			$cat_id = $cat;
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
				'all'		=> 'date_added DESC',
				'mr'		=> 'date_added DESC',
				'mv'		=> 'views DESC',
				'ra'		=> 'RAND()'

					);
	if($order != 'fr')
    {
	$orderby = 	$orders[$order];
    }
	else
    {
	$featured = 'yes';
    }

	//$sql = "SELECT * FROM video WHERE $cat_query  AND $query_param $orderby $query_limit";
	//$sql_p = "SELECT * FROM video WHERE $cat_query  AND $query_param $orderby";
	$sql = $myquery->getVideoList(array(
										'category'=>$cat_id,
										'feature'=>$featured,
										'order'=>$orderby,
										'limit'=>$query_limit
										),true,'query');
	$sql_p = $myquery->getVideoList(array(
										'category'=>$cat_id,
										'feature'=>$featured,
										'order'=>$orderby,
										'limit'=>'nolimit'
										),true,'query');
	$vdo_data = $db->Execute($sql);
	$total_vdo = $vdo_data->recordcount() + 0;
	$videos = $vdo_data->getrows();
	
	
	Assign('videos',$videos);
	Assign('ct','&amp;ct='.$cat);
	
//Pagination
$link = '?ct='.$cat.'&amp;order='.$order;
Assign('link',$link);

	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	//$all_pages[0]['page'] = $page_id;
	$records = $total_rows/$limit;
	$total_pages = round($records+0.49,0);
	
$show_pages = ShowPagination($total_pages,$page,$link);
Assign('show_pages',$show_pages);

Assign('pages',$pages);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);


Template('header.html');
Template('message.html');
Template('videos.html');
Template('footer.html');

?>
 