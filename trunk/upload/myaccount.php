<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/
define("THIS_PAGE",'myaccount');
define("PARENT_PAGE",'home');

require 'includes/config.inc.php';
$userquery->logincheck();

setup_myaccount_dashboard();

subtitle(lang("my_account"));
assign('user',$userquery->get_user_details(userid()));
template_files('myaccount.html');
display_it();
?>