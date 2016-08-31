<?php

	/**
	* File: Photos
	* Description: Used to display list of photos
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'photos');
	define("PARENT_PAGE",'photos');
	require 'includes/config.inc.php';
	global $cbphoto, $cbcollection, $pages;
	$pages->page_redir();
	$assign_arry = array();
	$sort = $_GET['sort'];
	$cond = array("category"=>mysql_clean($_GET['cat']),"date_span"=>$_GET['time'], "active"=>"yes");
	$table_name = "photos";
	$cond = build_sort_photos($sort, $cond);
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,MAINPLIST);
	$clist = $cond;
	$clist['limit'] = $get_limit;
	$photos = get_photos($clist);
	$collections = $cbcollection->get_collections($clist);
	//Collecting Data for Pagination
	$ccount = $cond;
	$ccount['count_only'] = true;
	$total_rows = get_photos($ccount);
	$total_pages = count_pages($total_rows,MAINPLIST);
	//Pagination
	$link==NULL;
	$extra_params=NULL;
	$tag='<li><a #params#>#page#</a><li>';
	$pages->paginate($total_pages,$page,$link,$extra_params,$tag);
	if (!$subtitle) {
		$subtitle = 'Photos';
	}
	subtitle(lang($subtitle));
	//Displaying The Template
	$assign_arry['photos'] = $photos;
	$assign_arry['collections'] = $collections;
	array_val_assign($assign_arry);
	template_files('photos.html');
	display_it();
?>