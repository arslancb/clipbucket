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


subtitle("ClipBucket Server Module Checker");
template_files("cb_mod_check.html");
display_it();
?>