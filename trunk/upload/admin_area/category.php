<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


//Form Processing

if(isset($_POST['add_cateogry'])){
	$cbvid->add_category($_POST);
}
	
//Edit Categoty
if(isset($_GET['category'])){
	$category = clean($_GET['category']);
		if($myquery->CategoryExists($category)){
				if(isset($_POST['update_category'])){
						if($myquery->UpdateCategory($_GET['category'])){
							$msg[] = "Category Has Been Updated";
						}
					}		
						
			$sql = "SELECT * from category WHERE categoryid = '".$category."'";
			$rs = $db->Execute($sql);
			$category_data = $rs->getrows();;
			Assign('category_data',$category_data);
			Assign('edit_category','show');			
			}else{
		$msg[] = $LANG['cat_exist_error'];
		}
	}

//Delete Category
if(isset($_GET['delete_category'])){
	$cbvid->delete_category($_GET['delete_category']);
}
	
//Assing Category Values
assign('category',$cbvid->get_categories());
assign('total',$cbvid->total_categories());

Assign('msg',@$msg);	
/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('category.html');
Template('footer.html');*/
$cbvid->get_default_category();

template_files('category.html');
display_it();

?>