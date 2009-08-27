<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';

subtitle('signup_success');

Assign('msg',@$msg);
Template('header.html');
Template('message.html');
Template('signup_success.html');
Template('footer.html');

?>