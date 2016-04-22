<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$pages->page_redir();
subtitle('privacy');
Template('header.html');
Template('message.html');
Template('privacy.html');
Template('footer.html');
?>