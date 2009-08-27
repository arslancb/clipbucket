<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author   : ArslanHassan																		|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();
subtitle('create_group');

//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	

//Form Validation And Submition
	if(isset($_POST['create'])){
		$msg = $groups->CreateGroup();
	}

//Assigning Default Values
@$values= array(
	'default_name' 		=> mysql_clean($_POST['name']),
	'default_des'		=> mysql_clean($_POST['description']),
	'default_tags' 		=> mysql_clean($_POST['tags']),
	'default_url'		=> mysql_clean($_POST['url']),
	'default_category'	=> mysql_clean($_POST['category']),
	'default_type'		=> mysql_clean($_POST['group_type']),
	'default_vtype'		=> mysql_clean($_POST['video_type']),
	'default_ptype'		=> mysql_clean($_POST['post_type'])
	);
	while(list($name,$value) = each($values)){
	Assign($name,$value);
	}

	
@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('create_group.html');
Template('footer.html');

?>