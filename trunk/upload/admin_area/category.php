<?php
/* 
 *******************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com & (Arslan Hassan). All rights reserved.
 | @ Author : ArslanHassan
 | @ Software : ClipBucket , © PHPBucket.com
 *******************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();


//Form Processing
if(isset($_POST['add_cateogry'])){
	$cbvid->add_category($_POST);
}

//Making Categoyr as Default
if(isset($_GET['make_default']))
{
	$cid = mysql_clean($_GET['make_default']);
	$cbvid->make_default_category($cid);
}

//Edit Categoty
if(isset($_GET['category'])){
	assign("edit_category","show");
	if(isset($_POST['update_category']))
	{
		$cbvid->update_category($_POST);
	}
	assign('cat_details',$cbvid->get_category($_GET['category']));
}

//Delete Category
if(isset($_GET['delete_category'])){
	$cbvid->delete_category($_GET['delete_category']);
}


$cats = $cbvid->get_categories();
$pid = $cbvid->get_category_field($_GET['category'],'parent_id');

if($pid)
	$selected = $pid;
	
$parent_cats = $cbvid->admin_area_cats($selected);


//Updating Category Order
if(isset($_POST['update_order']))
{
	foreach($cats as $cat)
	{
		if(!empty($cat['category_id']))
		{
			$order = $_POST['category_order_'.$cat['category_id']];
			$cbvid->update_cat_order($cat['category_id'],$order);
		}
	}
	
	$cats = $cbvid->get_categories();

}

//Assing Category Values
assign('category',$cats);
assign('parent_cats',$parent_cats);
assign('total',$cbvid->total_categories());

subtitle("Video Category Manager");
Assign('msg',@$msg);	
template_files('category.html');
display_it();

?>