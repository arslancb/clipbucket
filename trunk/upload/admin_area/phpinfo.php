<?php
/* 
 ********************************************************************
 | Copyright (c) 2007-2012 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , � PHPBucket.com														|
 ********************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
subtitle("Phpinfo");

ob_start();
phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();


$phpinfo = preg_replace('/\<\!DOCTYPE(.*)\.dtd\"\>/','',$phpinfo);
$phpinfo = preg_replace('/<style[^>]*?>.*?<\/style>/si','',$phpinfo);
$phpinfo = preg_replace('/<title>.*?"center">/si','',$phpinfo);
$phpinfo = preg_replace('/<html><head>/','',$phpinfo);
$phpinfo = preg_replace ('/<\/div><\/body><\/html>/', '', $phpinfo);

//Add Class
$phpinfo = preg_replace('/<table border="0" cellpadding="3" width="600">/',
        '<table border="0" class="table table-striped table-bordered" cellpadding="3" width="600">',$phpinfo);


assign('phpinfo',$phpinfo);
template_files('phpinfo.html');
display_it();
?>