<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define("THIS_PAGE",'myaccount');

require 'includes/config.inc.php';
$userquery->logincheck();

assign('user',$userquery->get_user_details(userid()));
template_files('myaccount.html');
display_it();
?>