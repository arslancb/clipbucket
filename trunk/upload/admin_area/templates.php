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
$userquery->perm_check('manage_template_access',true);

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Templates And Players');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Templates Manager');
}

if($_GET['change'])
{
	$myquery->set_template($_GET['change']);
}

subtitle("Template Manager");
template_files('templates.html');
display_it();
?>