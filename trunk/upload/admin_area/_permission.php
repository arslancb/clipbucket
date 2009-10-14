<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


//Adding Permission
if(isset($_POST['add_permission']))
{
	$userquery->add_new_permission($_POST);
}
//Deleting Permission
if(!empty($_GET['perm_del']))
{
	$userquery->remove_permission($_GET['perm_del']);
}

template_files('_permission.html');
display_it();


?>