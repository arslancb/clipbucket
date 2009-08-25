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
	if($myquery->AddCategory()){
	$msg[] = $LANG['cat_add_msg'];
	}else{
	$msg[] = $LANG['cat_img_error'];
	}
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
$category = mysql_clean($_GET['delete_category']);
	if($myquery->CategoryExists($category)){
	$msg[] = $myquery->DeleteCategory($category);
	}else{
	$msg[] = $LANG['cat_exist_error'];
	}
}
	
//Assing Category Values

		$sql = "SELECT * from category";
		$rs = $db->Execute($sql);
		$total = $rs->recordcount() + 0;
		$category = $rs->getrows();
		
		Assign('total', $total + 0);
		Assign('category', $category);	

Assign('msg',@$msg);	
/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('category.html');
Template('footer.html');*/

template_files('category.html');
display_it();

?>