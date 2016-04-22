<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();



if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Videos');
}
if(!defined('SUB_PAGE')){
		define('SUB_PAGE', "Editor's Pick");
}


$vid = $_GET['vid'];


subtitle("Editor's Pick");
template_files(CB_EP_BASEDIR.'/admin/styles/special_thumb.html');

?>