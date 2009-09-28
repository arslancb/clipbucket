<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$pages->page_redir();
$userquery->login_check('admin_access');

if($_GET['change'])
{
	$myquery->set_template($_GET['change']);
}
template_files('templates.html');
display_it();
?>