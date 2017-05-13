<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Tool Box');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'PHP extensions info');
}

$post_max_size = ini_get('post_max_size');
$memory_limit = ini_get('memory_limit');
$upload_max_filesize = ini_get('upload_max_filesize');
$max_execution_time = ini_get('max_execution_time');

assign("post_max_size",$post_max_size);
assign("memory_limit",$memory_limit);
assign("upload_max_filesize",$upload_max_filesize);
assign("max_execution_time",$max_execution_time);
assign('VERSION',VERSION);

subtitle("ClipBucket Server Module Checker");
template_files("cb_server_conf_info.html");
display_it();
?>