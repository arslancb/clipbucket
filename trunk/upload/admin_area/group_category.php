<?php
/* 
 *******************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com & (Arslan Hassan). All rights reserved.
 | @ Author : ArslanHassan
 | @ Software : ClipBucket , © PHPBucket.com
 *******************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->perm_check('group_moderation',true);
//Form Processing
if(isset($_POST['add_cateogry'])){
	$cbgroup->add_category($_POST);
}

//Making Categoyr as Default
if(isset($_GET['make_default']))
{
	$cid = mysql_clean($_GET['make_default']);
	$cbgroup->make_default_category($cid);
}

//Edit Categoty
if(isset($_GET['category'])){
	assign("edit_category","show");
	if(isset($_POST['update_category']))
	{
		$cbgroup->update_category($_POST);
	}
	assign('cat_details',$cbgroup->get_category($_GET['category']));
}

//Delete Category
if(isset($_GET['delete_category'])){
	$cbgroup->delete_category($_GET['delete_category']);
}
	
$cats = getCategoryList(array('type'=>'group'));
//Updating Category Order
if(isset($_POST['update_order']))
{
	foreach($cats as $cat)
	{
		if(!empty($cat['category_id']))
		{
			$order = $_POST['category_order_'.$cat['category_id']];
			$cbgroup->update_cat_order($cat['category_id'],$order);
		}
	}
	$cats = getCategoryList(array('type'=>'group'));
}

assign('manage_categories_title','Manage group categories');
assign('type','group');
$Smarty->assign_by_ref('obj',$cbgroup);

//Assing Category Values
assign('category',$cats);
assign('categories',$cats);
assign('total',$cbgroup->total_categories());

subtitle("Groups Category manager");	
template_files('category.html'); display_it();

?>