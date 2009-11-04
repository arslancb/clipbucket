<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$userquery->perm_check('view_channel',true);

//Getting Video List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);
$users = $db->select("users",'*',$cond,$get_limit,"doj DESC");
Assign('users', $users);	

//Collecting Data for Pagination
$total_rows  = $db->count('users','*',$cond);
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);

template_files('channels.html');
display_it();
?>