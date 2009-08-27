<?php
/*
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

$check = $_REQUEST['check'];

Template('header.html');
Template('leftmenu.html');
Template('message.html');
test_exec( 'bash -version' );
test_exec( $check.' -version' );

Template('footer.html');
?>