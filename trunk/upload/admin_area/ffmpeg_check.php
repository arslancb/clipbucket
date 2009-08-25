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
	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
test_exec( 'bash -version' );
test_exec( 'ldd '.FFMPEG_BINARY );
test_exec( FFMPEG_BINARY.' -version' );
test_exec( FFMPEG_BINARY.' -formats' );

Template('footer.html');
?>